<?php

namespace App\Http\Controllers;

use App\Services\ResearchFeeNoticeDocumentService;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ResearchFeePaymentController extends Controller
{
    private const EXCLUDED_DEPARTMENT_ID = 7;

    public function __construct(
        private readonly ResearchFeeNoticeDocumentService $noticeDocuments
    ) {}

    public function index(Request $request): View
    {
        $filters = $this->validatedFilters($request);
        $query = $this->filteredQuery($filters);

        $rows = (clone $query)
            ->orderBy('fr.depart_id')
            ->orderBy('fr.level')
            ->orderBy('fr.couse')
            ->orderBy('fr.std_code')
            ->paginate(25)
            ->withQueryString();

        return view('research-fee.payments', [
            ...$this->viewData($filters, $query),
            'rows' => $rows,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'std_code' => ['required', 'string', 'max:50'],
            'term' => ['required', 'integer', 'in:1,2'],
            'year' => ['required', 'integer', 'min:2555', 'max:2600'],
            'status' => ['required', Rule::in(['1', '2', '3'])],
            'amount' => ['nullable', 'numeric', 'min:0'],
            'slip_no' => ['nullable', 'string', 'max:250'],
        ]);

        $paid = $data['status'] === '3';

        DB::connection('eoffice')
            ->table('fee_research')
            ->where('std_code', $data['std_code'])
            ->where('term', (string) $data['term'])
            ->where('year', (string) $data['year'])
            ->where('depart_id', '!=', self::EXCLUDED_DEPARTMENT_ID)
            ->update([
                'status' => $data['status'],
                'amount' => $paid ? (float) ($data['amount'] ?? 0) : 0,
                'slip_no' => $paid ? ($data['slip_no'] ?: null) : null,
            ]);

        return back()->with('success', 'บันทึกสถานะของ '.$data['std_code'].' เรียบร้อยแล้ว');
    }

    public function report(Request $request): View
    {
        $filters = $this->validatedFilters($request);
        $query = $this->filteredQuery($filters);

        $rows = (clone $query)
            ->orderBy('fr.depart_id')
            ->orderBy('fr.level')
            ->orderBy('fr.couse')
            ->orderBy('fr.std_code')
            ->get();

        return view('research-fee.report', [
            ...$this->viewData($filters, $query),
            'rows' => $rows,
        ]);
    }

    public function studentNotice(Request $request): BinaryFileResponse
    {
        $student = $this->findStudent($request);
        $document = $this->noticeDocuments->makeStudentNotice($student, now());

        return response()
            ->download($document['path'], $document['filename'], [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            ])
            ->deleteFileAfterSend(true);
    }

    public function sponsorNotice(Request $request): BinaryFileResponse
    {
        $student = $this->findStudent($request);
        $document = $this->noticeDocuments->makeSponsorNotice($student, now());

        return response()
            ->download($document['path'], $document['filename'], [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            ])
            ->deleteFileAfterSend(true);
    }

    /**
     * @return array{term:int, year:int, depart_id:int, status:string, q:string}
     */
    private function validatedFilters(Request $request): array
    {
        $currentYear = (int) date('Y') + 543;
        $validated = $request->validate([
            'term' => ['nullable', 'integer', 'in:1,2'],
            'year' => ['nullable', 'integer', 'min:2555', 'max:2600'],
            'depart_id' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', Rule::in(['', '1', '2', '3'])],
            'q' => ['nullable', 'string', 'max:100'],
        ]);

        return [
            'term' => (int) ($validated['term'] ?? 1),
            'year' => (int) ($validated['year'] ?? $currentYear),
            'depart_id' => (int) ($validated['depart_id'] ?? 0),
            'status' => (string) ($validated['status'] ?? ''),
            'q' => trim((string) ($validated['q'] ?? '')),
        ];
    }

    /**
     * @param array{term:int, year:int, depart_id:int, status:string, q:string} $filters
     */
    private function filteredQuery(array $filters): Builder
    {
        return DB::connection('eoffice')
            ->table('fee_research as fr')
            ->leftJoin('depart_fee_research as d', 'fr.depart_id', '=', 'd.depart_id')
            ->where('fr.term', (string) $filters['term'])
            ->where('fr.year', (string) $filters['year'])
            ->where('fr.depart_id', '!=', self::EXCLUDED_DEPARTMENT_ID)
            ->when($filters['depart_id'] > 0, fn (Builder $query) => $query
                ->where('fr.depart_id', $filters['depart_id']))
            ->when($filters['status'] !== '', fn (Builder $query) => $query
                ->where('fr.status', $filters['status']))
            ->when($filters['q'] !== '', function (Builder $query) use ($filters) {
                $like = '%'.$filters['q'].'%';
                $query->where(function (Builder $inner) use ($like) {
                    $inner->where('fr.std_code', 'like', $like)
                        ->orWhere('fr.name', 'like', $like)
                        ->orWhere('fr.couse', 'like', $like);
                });
            })
            ->select('fr.*', 'd.depart_name');
    }

    /**
     * @param array{term:int, year:int, depart_id:int, status:string, q:string} $filters
     * @return array<string, mixed>
     */
    private function viewData(array $filters, Builder $query): array
    {
        $summary = DB::connection('eoffice')
            ->query()
            ->fromSub((clone $query)->select([
                'fr.status',
                'fr.amount',
                'fr.std_code',
            ]), 'filtered')
            ->selectRaw('COUNT(*) as total')
            ->selectRaw("SUM(CASE WHEN status = '1' THEN 1 ELSE 0 END) as pending")
            ->selectRaw("SUM(CASE WHEN status = '2' THEN 1 ELSE 0 END) as exempt")
            ->selectRaw("SUM(CASE WHEN status = '3' THEN 1 ELSE 0 END) as paid")
            ->selectRaw("SUM(CASE WHEN status = '3' THEN amount ELSE 0 END) as received")
            ->first();

        return [
            'filters' => $filters,
            'summary' => $summary,
            'departments' => DB::connection('eoffice')
                ->table('depart_fee_research')
                ->where('depart_id', '!=', self::EXCLUDED_DEPARTMENT_ID)
                ->orderBy('depart_id')
                ->get(),
            'years' => range(2575, 2555),
        ];
    }

    private function findStudent(Request $request): object
    {
        $data = $request->validate([
            'std_code' => ['required', 'string', 'max:50'],
            'term' => ['required', 'integer', 'in:1,2'],
            'year' => ['required', 'integer', 'min:2555', 'max:2600'],
        ]);

        return DB::connection('eoffice')
            ->table('fee_research as fr')
            ->leftJoin('depart_fee_research as d', 'fr.depart_id', '=', 'd.depart_id')
            ->where('fr.std_code', $data['std_code'])
            ->where('fr.term', (string) $data['term'])
            ->where('fr.year', (string) $data['year'])
            ->where('fr.depart_id', '!=', self::EXCLUDED_DEPARTMENT_ID)
            ->select('fr.*', 'd.depart_name')
            ->firstOrFail();
    }
}
