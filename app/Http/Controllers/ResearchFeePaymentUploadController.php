<?php

namespace App\Http\Controllers;

use App\Services\ResearchFeePaymentUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ResearchFeePaymentUploadController extends Controller
{
    public function __construct(
        private readonly ResearchFeePaymentUploadService $uploadService
    ) {}

    public function index(Request $request): View
    {
        $currentYear = (int) date('Y') + 543;
        $preview = session('payment_upload_preview');

        return view('research-fee.payment-upload', [
            'term' => (int) old('term', $request->query('term', $this->defaultTerm())),
            'year' => (int) old('year', $request->query('year', $currentYear)),
            'years' => range(2575, 2555),
            'preview' => is_array($preview) ? $preview : null,
            'result' => session('payment_upload_result'),
        ]);
    }

    public function preview(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'term' => ['required', 'integer', 'in:1,2'],
            'year' => ['required', 'integer', 'min:2555', 'max:2600'],
            'file' => ['required', 'file', 'mimes:xlsx,xls', 'max:10240'],
        ], [
            'file.required' => 'กรุณาเลือกไฟล์ Excel',
            'file.mimes' => 'รองรับเฉพาะไฟล์ Excel (.xlsx, .xls)',
            'file.max' => 'ขนาดไฟล์ต้องไม่เกิน 10MB',
        ]);

        $preview = $this->uploadService->preview(
            $request->file('file'),
            (int) $data['term'],
            (int) $data['year']
        );

        $token = bin2hex(random_bytes(16));
        $preview['token'] = $token;

        return redirect()
            ->route('research-fee.payments.upload', [
                'term' => $data['term'],
                'year' => $data['year'],
            ])
            ->with('payment_upload_preview', $preview)
            ->with('success', sprintf(
                'อ่านไฟล์สำเร็จ %d รายการ — พบตรงกัน %d, ไม่พบ %d, ชื่อซ้ำ %d',
                $preview['total_rows'],
                count($preview['matched']),
                count($preview['unmatched']),
                count($preview['ambiguous'])
            ));
    }

    public function confirm(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'token' => ['required', 'string', 'size:32'],
        ]);

        $preview = session('payment_upload_preview');
        if (! is_array($preview) || ($preview['token'] ?? null) !== $data['token']) {
            return redirect()
                ->route('research-fee.payments.upload')
                ->with('error', 'ข้อมูลตรวจสอบหมดอายุหรือไม่ถูกต้อง กรุณาอัปโหลดไฟล์ใหม่');
        }

        if (($preview['matched'] ?? []) === []) {
            return redirect()
                ->route('research-fee.payments.upload', [
                    'term' => $preview['term'] ?? null,
                    'year' => $preview['year'] ?? null,
                ])
                ->with('payment_upload_preview', $preview)
                ->with('error', 'ไม่มีรายการที่ตรงกับระบบให้บันทึก');
        }

        $result = $this->uploadService->confirm(
            (int) $preview['term'],
            (int) $preview['year'],
            $preview['matched']
        );

        return redirect()
            ->route('research-fee.payments.upload', [
                'term' => $preview['term'],
                'year' => $preview['year'],
            ])
            ->with('payment_upload_result', [
                ...$result,
                'storage_path' => $preview['storage_path'] ?? null,
                'storage_disk' => $preview['storage_disk'] ?? null,
                'original_name' => $preview['original_name'] ?? null,
                'matched_count' => count($preview['matched']),
                'unmatched_count' => count($preview['unmatched'] ?? []),
                'ambiguous_count' => count($preview['ambiguous'] ?? []),
            ])
            ->with('success', 'บันทึกการชำระเงินเรียบร้อย '.$result['updated'].' รายการ');
    }

    private function defaultTerm(): int
    {
        $month = (int) date('n');

        return ($month >= 6 && $month <= 10) ? 1 : 2;
    }
}
