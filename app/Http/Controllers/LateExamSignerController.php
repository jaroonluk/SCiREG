<?php

namespace App\Http\Controllers;

use App\Models\DocumentSigner;
use App\Services\DocumentSignerService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LateExamSignerController extends Controller
{
    public function __construct(
        private readonly DocumentSignerService $signerService
    ) {}

    public function index(): View
    {
        return view('late-exam.signers', [
            'executives' => $this->signerService->selectableExecutives(),
            'settings' => $this->signerService->currentSettings(),
            'roleLabels' => DocumentSigner::roleLabels(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $usernames = $this->signerService->selectableExecutives()
            ->pluck('username')
            ->all();

        $data = $request->validate([
            'username' => ['required', 'string', 'max:100', Rule::in($usernames)],
            'signing_role' => ['required', 'string', Rule::in(array_keys(DocumentSigner::roleLabels()))],
        ], [
            'username.required' => 'กรุณาเลือกผู้บริหาร',
            'username.in' => 'ไม่พบผู้บริหารที่เลือก',
            'signing_role.required' => 'กรุณาเลือกประเภทการลงนาม',
            'signing_role.in' => 'ประเภทการลงนามไม่ถูกต้อง',
        ]);

        try {
            $this->signerService->save(
                $data['username'],
                $data['signing_role'],
                $request->user()?->username
            );
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }

        return back()->with('success', 'บันทึกผู้บริหารสำหรับลงนามเอกสารเรียบร้อยแล้ว');
    }
}
