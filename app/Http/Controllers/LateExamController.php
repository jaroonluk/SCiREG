<?php

namespace App\Http\Controllers;

use App\Models\FormLate;
use App\Services\DocumentSignerService;
use App\Services\LateExamService;
use App\Services\LateExamTermSettingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LateExamController extends Controller
{
    public function __construct(
        private readonly LateExamService $lateExam,
        private readonly DocumentSignerService $signers,
        private readonly LateExamTermSettingService $termSetting,
    ) {}

    public function importIndex(Request $request): View
    {
        $defaults = $this->termSetting->current();
        $yearListMax = max($defaults['year'], (int) date('Y') + 543) + 1;
        $term = (int) $request->query('term', $defaults['term']);
        $year = (int) $request->query('year', $defaults['year']);

        $summary = null;
        if ($request->boolean('preview')) {
            $summary = $this->lateExam->previewImport($term, $year);
        }

        return view('late-exam.import', [
            'term' => $term,
            'year' => $year,
            'years' => range($yearListMax, 2555),
            'summary' => $summary,
            'result' => session('import_result'),
        ]);
    }

    public function importStore(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'term' => ['required', 'integer', 'in:1,2'],
            'year' => ['required', 'integer', 'min:2555', 'max:2600'],
        ]);

        $result = $this->lateExam->importFromReg((int) $data['term'], (int) $data['year']);

        return redirect()
            ->route('late-exam.import', [
                'term' => $data['term'],
                'year' => $data['year'],
                'preview' => 1,
            ])
            ->with('import_result', $result)
            ->with('success', 'นำเข้าข้อมูลเรียบร้อยแล้ว '.$result['imported'].' รายการ');
    }

    public function recordIndex(): View
    {
        $defaults = $this->termSetting->current();
        $yearListMax = max($defaults['year'], (int) date('Y') + 543) + 1;

        return view('late-exam.record', [
            'reasons' => $this->lateExam->reasons(),
            'years' => range($yearListMax, 2555),
            'defaultYear' => $defaults['year'],
            'defaultTerm' => $defaults['term'],
            'defaultExamType' => $defaults['exam_type'],
        ]);
    }

    public function lookup(Request $request): JsonResponse
    {
        $data = $request->validate([
            'q' => ['required', 'string', 'max:30'],
        ]);

        $students = $this->lateExam->searchStudents($data['q']);

        return response()->json([
            'ok' => true,
            'students' => $students,
            'count' => count($students),
        ]);
    }

    public function lookupCourse(Request $request): JsonResponse
    {
        $data = $request->validate([
            'q' => ['required', 'string', 'max:30'],
        ]);

        $courses = $this->lateExam->searchCourses($data['q']);

        return response()->json([
            'ok' => true,
            'courses' => $courses,
            'count' => count($courses),
        ]);
    }

    public function lookupDepartment(Request $request): JsonResponse
    {
        $data = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
        ]);

        $departments = $this->lateExam->searchDepartments((string) ($data['q'] ?? ''));

        return response()->json([
            'ok' => true,
            'departments' => $departments,
            'count' => count($departments),
        ]);
    }

    public function recordStore(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'STUDENTID' => ['nullable'],
            'STUDENTCODE' => ['required', 'string', 'max:20'],
            'STUDENT_NAME' => ['required', 'string', 'max:255'],
            'PROGRAM_NAME' => ['nullable', 'string', 'max:255'],
            'DEPARTMENT_NAME' => ['nullable', 'string', 'max:255'],
            'COURSE_CODE' => ['required', 'string', 'max:20'],
            'COURSE_NAME' => ['nullable', 'string', 'max:255'],
            'ROOM_NAME' => ['nullable', 'string', 'max:100'],
            'SEAT_NO' => ['nullable', 'string', 'max:20'],
            'SEMESTER' => ['required', 'integer', 'in:1,2'],
            'EXAM_TYPE' => ['required', 'string', 'in:M,F'],
            'ACADYEAR' => ['required', 'integer', 'min:2555', 'max:2600'],
            'ReasonID' => ['required', 'integer'],
            'DESCI' => ['nullable', 'string', 'max:254'],
        ], [
            'STUDENTCODE.required' => 'กรุณากรอกรหัสนักศึกษา',
            'STUDENT_NAME.required' => 'กรุณากรอกชื่อ–สกุลนักศึกษา',
            'COURSE_CODE.required' => 'กรุณากรอกรหัสวิชา',
            'ReasonID.required' => 'กรุณาเลือกสาเหตุ',
        ]);

        $reason = $this->lateExam->reasons()->firstWhere('ReasonID', (int) $data['ReasonID']);
        if ($reason && $reason->ReasonName === 'อื่น ๆ' && blank($data['DESCI'] ?? null)) {
            return back()
                ->withInput()
                ->with('error', 'กรุณาระบุรายละเอียดเมื่อเลือกสาเหตุ อื่น ๆ');
        }

        $userDepartment = trim((string) ($data['DEPARTMENT_NAME'] ?? ''));

        $resolved = $this->lateExam->lookupStudent($data['STUDENTCODE']);
        if ($resolved) {
            $data['STUDENTID'] = $resolved['STUDENTID'];
            $data['STUDENT_NAME'] = $resolved['full_name'];
        }

        if ($userDepartment !== '') {
            $data['DEPARTMENT_NAME'] = $userDepartment;
        } elseif ($resolved && filled($resolved['DEPARTMENTNAME'] ?? null)) {
            $data['DEPARTMENT_NAME'] = $resolved['DEPARTMENTNAME'];
        } else {
            $data['DEPARTMENT_NAME'] = null;
        }

        $form = $this->lateExam->recordLate([
            ...$data,
            'STUDENTID' => $data['STUDENTID'] !== null && $data['STUDENTID'] !== ''
                ? $data['STUDENTID']
                : 0,
            'PROGRAM_NAME' => null,
            'CREATED_BY' => $request->user()?->username,
            'LATETIME' => now(),
            'CLASSID' => 0,
            'COURSEID' => 0,
            'ROOMID' => 0,
        ]);

        return redirect()
            ->route('late-exam.print.show', ['id' => $form->formID, 'from' => 'record'])
            ->with('success', 'บันทึกการเข้าสอบช้าเรียบร้อยแล้ว');
    }

    public function printIndex(Request $request): View
    {
        $defaults = $this->termSetting->current();
        $yearListMax = max($defaults['year'], (int) date('Y') + 543) + 1;
        $year = $request->filled('year') ? (int) $request->query('year') : $defaults['year'];
        $term = $request->filled('term') ? (int) $request->query('term') : $defaults['term'];
        $examType = strtoupper((string) $request->query('exam_type', $defaults['exam_type']));
        if (! in_array($examType, ['M', 'F'], true)) {
            $examType = $defaults['exam_type'];
        }
        $records = $this->lateExam->listPrintable($year, $term ?: null, $examType);

        return view('late-exam.print', [
            'year' => $year,
            'term' => $term,
            'examType' => $examType,
            'years' => range($yearListMax, 2555),
            'records' => $records,
            'filtered' => true,
            'signer' => $this->signers->activeSigner(),
        ]);
    }

    public function printShow(Request $request, int $id): View
    {
        $ids = $request->filled('ids')
            ? collect(explode(',', (string) $request->query('ids')))
                ->map(fn ($v) => (int) trim($v))
                ->filter()
                ->unique()
                ->values()
            : collect([$id]);

        if (! $ids->contains($id)) {
            $ids = $ids->prepend($id)->unique()->values();
        }

        $records = FormLate::query()
            ->whereIn('formID', $ids->all())
            ->orderBy('formID')
            ->get();

        abort_if($records->isEmpty(), 404);

        $from = (string) $request->query('from', 'print');
        if (! in_array($from, ['print', 'record'], true)) {
            $from = 'print';
        }

        return view('late-exam.print-form', [
            'records' => $records,
            'signer' => $this->signers->activeSigner(),
            'backRoute' => $from === 'record' ? 'late-exam.record' : 'late-exam.print',
            'backLabel' => $from === 'record' ? 'กลับหน้าบันทึก' : 'กลับรายการพิมพ์',
        ]);
    }

    public function printDestroy(Request $request, int $id): RedirectResponse
    {
        $this->lateExam->deleteRecord($id);

        $defaults = $this->termSetting->current();
        $year = $request->filled('year') ? (int) $request->input('year') : $defaults['year'];
        $term = $request->filled('term') ? (int) $request->input('term') : $defaults['term'];
        $examType = strtoupper((string) $request->input('exam_type', $defaults['exam_type']));
        if (! in_array($examType, ['M', 'F'], true)) {
            $examType = $defaults['exam_type'];
        }

        return redirect()
            ->route('late-exam.print', [
                'year' => $year,
                'term' => $term,
                'exam_type' => $examType,
            ])
            ->with('success', 'ลบรายการผู้เข้าสอบช้าเรียบร้อยแล้ว');
    }

    public function summaryIndex(Request $request): View
    {
        [$year, $terms, $examTypes, $yearListMax] = $this->summaryFilters($request);
        $summary = $this->lateExam->summary($year, $terms, $examTypes);

        return view('late-exam.summary', [
            'year' => $year,
            'terms' => $terms,
            'examTypes' => $examTypes,
            'years' => range($yearListMax, 2555),
            'summary' => $summary,
            'ran' => true,
        ]);
    }

    public function summaryExport(Request $request): StreamedResponse
    {
        [$year, $terms, $examTypes] = $this->summaryFilters($request);
        $summary = $this->lateExam->summary($year, $terms, $examTypes);

        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('รายการเข้าสอบช้า');

        $termText = collect($terms)->map(fn ($t) => (int) $t === 1 ? 'ต้น' : 'ปลาย')->implode(', ');
        $examText = collect($examTypes)->map(fn ($t) => $t === 'M' ? 'กลางภาค' : 'ปลายภาค')->implode(', ');

        $sheet->mergeCells('A1:L1');
        $sheet->mergeCells('A2:L2');
        $sheet->setCellValue('A1', 'รายงานสรุปการเข้าสอบช้านักศึกษา คณะวิทยาศาสตร์');
        $sheet->setCellValue('A2', 'ปีการศึกษา '.$year.' · ภาค'.$termText.' · '.$examText);
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A2')->getFont()->setSize(11);

        $headers = [
            'A4' => 'ลำดับ',
            'B4' => 'วันเวลา',
            'C4' => 'รหัสนักศึกษา',
            'D4' => 'ชื่อ–สกุล',
            'E4' => 'สาขาวิชา',
            'F4' => 'รหัสวิชา',
            'G4' => 'ชื่อวิชา',
            'H4' => 'ห้องสอบ',
            'I4' => 'ภาคการศึกษา',
            'J4' => 'ช่วงสอบ',
            'K4' => 'สาเหตุ',
            'L4' => 'รายละเอียด',
        ];
        foreach ($headers as $cell => $label) {
            $sheet->setCellValue($cell, $label);
        }
        $sheet->getStyle('A4:L4')->getFont()->setBold(true);
        $sheet->getStyle('A4:L4')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('F3D06A');
        $sheet->getStyle('A4:L4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $rowNum = 5;
        foreach ($summary['rows'] as $index => $row) {
            $sheet->setCellValue('A'.$rowNum, $index + 1);
            $sheet->setCellValue('B'.$rowNum, optional($row->LATETIME)->format('d/m/Y H:i') ?? '');
            $sheet->setCellValueExplicit('C'.$rowNum, (string) ($row->STUDENTCODE ?? ''), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('D'.$rowNum, $row->STUDENT_NAME ?? '');
            $sheet->setCellValue('E'.$rowNum, $row->DEPARTMENT_NAME ?? '');
            $sheet->setCellValueExplicit('F'.$rowNum, (string) ($row->COURSE_CODE ?? ''), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('G'.$rowNum, $row->COURSE_NAME ?? '');
            $sheet->setCellValue('H'.$rowNum, $row->ROOM_NAME ?? '');
            $sheet->setCellValue('I'.$rowNum, (int) $row->SEMESTER === 1 ? 'ต้น' : ((int) $row->SEMESTER === 2 ? 'ปลาย' : ''));
            $sheet->setCellValue('J'.$rowNum, $row->EXAM_TYPE === 'M' ? 'กลางภาค' : ($row->EXAM_TYPE === 'F' ? 'ปลายภาค' : ''));
            $sheet->setCellValue('K'.$rowNum, $row->REASON_NAME ?? '');
            $sheet->setCellValue('L'.$rowNum, $row->DESCI ?? '');
            $rowNum++;
        }

        $lastDataRow = max(4, $rowNum - 1);
        $sheet->getStyle('A4:L'.$lastDataRow)->getBorders()->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);
        foreach (range('A', 'L') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = sprintf(
            'late-exam-summary-%d-%s.xlsx',
            $year,
            now()->format('Ymd-His')
        );

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            $spreadsheet->disconnectWorksheets();
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * @return array{0:int,1:array<int,int>,2:array<int,string>,3:int}
     */
    private function summaryFilters(Request $request): array
    {
        $defaults = $this->termSetting->current();
        $yearListMax = max($defaults['year'], (int) date('Y') + 543) + 1;
        $year = (int) $request->query('year', $defaults['year']);

        $defaultTerms = [$defaults['term']];
        $terms = array_map('intval', (array) $request->query('terms', $defaultTerms));
        $terms = array_values(array_intersect($terms, [1, 2]));
        if ($terms === []) {
            $terms = $defaultTerms;
        }

        $examTypes = array_values(array_intersect(
            (array) $request->query('exam_types', [$defaults['exam_type']]),
            ['M', 'F']
        ));
        if ($examTypes === []) {
            $examTypes = [$defaults['exam_type']];
        }

        return [$year, $terms, $examTypes, $yearListMax];
    }
}
