<?php

namespace App\Http\Controllers;

use App\Services\ResearchFeeSummaryReportService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ResearchFeeSummaryReportController extends Controller
{
    private const FONT_NAME = 'TH Sarabun New';

    public function __construct(
        private readonly ResearchFeeSummaryReportService $reportService
    ) {}

    public function index(Request $request): View
    {
        $filters = $this->validatedFilters($request);
        $report = $this->reportService->build(
            $filters['term'],
            $filters['year'],
            $filters['depart_id']
        );

        $user = $request->user();
        $departmentLocked = $user?->isDepartmentOfficer() ?? false;

        return view('research-fee.summary-report', [
            'filters' => $filters,
            'report' => $report,
            'departments' => $departmentLocked
                ? $this->reportService->departments($filters['depart_id'])
                : $this->reportService->allDepartments(),
            'departmentLocked' => $departmentLocked,
            'years' => range(2575, 2555),
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        $filters = $this->validatedFilters($request);
        $report = $this->reportService->build(
            $filters['term'],
            $filters['year'],
            $filters['depart_id']
        );

        $spreadsheet = $this->makeSpreadsheet($filters, $report);
        $filename = sprintf(
            'research-fee-report-%d-term%d.xlsx',
            $filters['year'],
            $filters['term']
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
     * @param array{term:int, year:int, depart_id:int} $filters
     * @param array{sections:list<array{depart_name:string,rows:list<array{code:string,total:int,paid:int,exempt:int,pending:int,amount:float}>,row_count:int}>, totals:array{total:int,paid:int,exempt:int,pending:int,amount:float}} $report
     */
    private function makeSpreadsheet(array $filters, array $report): Spreadsheet
    {
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('รายงานค่าธรรมเนียม');

        $sheet->mergeCells('A1:G1');
        $sheet->mergeCells('A2:G2');
        $sheet->mergeCells('A3:G3');
        $sheet->setCellValue('A1', 'รายงาน การชำระเงินค่าธรรมเนียมวิจัย');
        $sheet->setCellValue('A2', 'สำหรับนักศึกษาระดับบัณฑิตศึกษา คณะวิทยาศาสตร์ มหาวิทยาลัยขอนแก่น');
        $sheet->setCellValue('A3', 'ปีการศึกษา '.$filters['year'].' ภาคการศึกษาที่ '.$filters['term']);

        $sheet->mergeCells('A5:A6');
        $sheet->mergeCells('B5:B6');
        $sheet->mergeCells('C5:F5');
        $sheet->mergeCells('G5:G6');
        $sheet->setCellValue('A5', 'สาขาวิชา');
        $sheet->setCellValue('B5', 'นักศึกษาแยกตามรหัสประจำตัว');
        $sheet->setCellValue('C5', 'จำนวนนักศึกษา');
        $sheet->setCellValue('C6', 'ทั้งหมด');
        $sheet->setCellValue('D6', 'ที่ชำระ');
        $sheet->setCellValue('E6', 'ที่ขอยกเว้น');
        $sheet->setCellValue('F6', 'ที่คงค้าง');
        $sheet->setCellValue('G5', 'จำนวนเงินที่รับชำระแล้ว');

        $row = 7;

        foreach ($report['sections'] as $section) {
            $startRow = $row;

            foreach ($section['rows'] as $index => $item) {
                if ($index === 0) {
                    $sheet->setCellValue('A'.$row, $section['depart_name']);
                }

                $sheet->setCellValue('B'.$row, 'รหัส '.$item['code']);
                $sheet->setCellValue('C'.$row, $item['total']);
                $sheet->setCellValue('D'.$row, $item['paid']);
                $sheet->setCellValue('E'.$row, $item['exempt']);
                $sheet->setCellValue('F'.$row, $item['pending']);
                $sheet->setCellValue('G'.$row, $item['amount']);
                $row++;
            }

            if ($section['row_count'] > 1) {
                $sheet->mergeCells('A'.$startRow.':A'.($row - 1));
            }
        }

        if ($report['sections'] !== []) {
            $sheet->mergeCells('A'.$row.':B'.$row);
            $sheet->setCellValue('A'.$row, 'รวม');
            $sheet->setCellValue('C'.$row, $report['totals']['total']);
            $sheet->setCellValue('D'.$row, $report['totals']['paid']);
            $sheet->setCellValue('E'.$row, $report['totals']['exempt']);
            $sheet->setCellValue('F'.$row, $report['totals']['pending']);
            $sheet->setCellValue('G'.$row, $report['totals']['amount']);
            $lastDataRow = $row;
        } else {
            $sheet->mergeCells('A7:G7');
            $sheet->setCellValue('A7', 'ไม่พบข้อมูล');
            $lastDataRow = 7;
        }

        $this->styleSheet($sheet, $lastDataRow, $report['sections'] !== []);

        return $spreadsheet;
    }

    private function styleSheet(Worksheet $sheet, int $lastDataRow, bool $hasData): void
    {
        $sheet->getColumnDimension('A')->setWidth(34);
        $sheet->getColumnDimension('B')->setWidth(28);
        $sheet->getColumnDimension('C')->setWidth(12);
        $sheet->getColumnDimension('D')->setWidth(12);
        $sheet->getColumnDimension('E')->setWidth(14);
        $sheet->getColumnDimension('F')->setWidth(12);
        $sheet->getColumnDimension('G')->setWidth(20);

        $sheet->getStyle('A1:G'.$lastDataRow)->applyFromArray([
            'font' => [
                'name' => self::FONT_NAME,
                'size' => 14,
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ]);

        $sheet->getStyle('A1:A3')->applyFromArray([
            'font' => [
                'name' => self::FONT_NAME,
                'size' => 16,
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        $sheet->getStyle('A5:G6')->applyFromArray([
            'font' => [
                'name' => self::FONT_NAME,
                'size' => 14,
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'F4E4B0'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        if ($hasData) {
            $sheet->getStyle('A7:G'.$lastDataRow)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
            ]);

            $sheet->getStyle('A7:A'.($lastDataRow - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle('B7:F'.$lastDataRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('G7:G'.$lastDataRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle('G7:G'.$lastDataRow)->getNumberFormat()->setFormatCode('#,##0.00');

            $sheet->getStyle('A'.$lastDataRow.':G'.$lastDataRow)->applyFromArray([
                'font' => [
                    'name' => self::FONT_NAME,
                    'bold' => true,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'F8EFCF'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ]);
            $sheet->getStyle('G'.$lastDataRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        } else {
            $sheet->getStyle('A7:G7')->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ]);
        }

        $sheet->getRowDimension(1)->setRowHeight(24);
        $sheet->getRowDimension(2)->setRowHeight(22);
        $sheet->getRowDimension(5)->setRowHeight(22);
        $sheet->getRowDimension(6)->setRowHeight(22);
    }

    /**
     * @return array{term:int, year:int, depart_id:int}
     */
    private function validatedFilters(Request $request): array
    {
        $currentYear = (int) date('Y') + 543;
        $validated = $request->validate([
            'term' => ['nullable', 'integer', 'in:1,2'],
            'year' => ['nullable', 'integer', 'min:2555', 'max:2600'],
            'depart_id' => ['nullable', 'integer', 'min:0'],
        ]);

        $departId = (int) ($validated['depart_id'] ?? 0);
        $user = $request->user();

        if ($user?->isDepartmentOfficer()) {
            $ownDepartId = $user->researchFeeDepartId();
            if (! $ownDepartId) {
                abort(403, 'ไม่พบสาขาวิชาที่สังกัดสำหรับบัญชีผู้ใช้นี้');
            }
            $departId = $ownDepartId;
        }

        return [
            'term' => (int) ($validated['term'] ?? 1),
            'year' => (int) ($validated['year'] ?? $currentYear),
            'depart_id' => $departId,
        ];
    }
}
