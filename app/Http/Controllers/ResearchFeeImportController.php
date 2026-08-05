<?php

namespace App\Http\Controllers;

use App\Services\ResearchFeeImportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ResearchFeeImportController extends Controller
{
    public function __construct(private ResearchFeeImportService $importService)
    {
    }

    public function index(Request $request): View
    {
        $currentBuddhistYear = (int) date('Y') + 543;
        $term = (int) $request->query('term', $this->defaultTerm());
        $year = (int) $request->query('year', $currentBuddhistYear);

        $summary = null;
        if ($request->boolean('preview')) {
            $summary = $this->importService->preview($term, $year);
        }

        return view('research-fee.import', [
            'term' => $term,
            'year' => $year,
            'years' => range(2575, 2555),
            'summary' => $summary,
            'result' => session('import_result'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'term' => ['required', 'integer', 'in:1,2'],
            'year' => ['required', 'integer', 'min:2555', 'max:2600'],
        ]);

        $result = $this->importService->import((int) $data['term'], (int) $data['year']);

        return redirect()
            ->route('research-fee.import', [
                'term' => $data['term'],
                'year' => $data['year'],
                'preview' => 1,
            ])
            ->with('import_result', $result)
            ->with('success', 'นำเข้าข้อมูลเรียบร้อยแล้ว '.$result['inserted'].' รายการ');
    }

    private function defaultTerm(): int
    {
        $month = (int) date('n');

        return ($month >= 6 && $month <= 10) ? 1 : 2;
    }
}
