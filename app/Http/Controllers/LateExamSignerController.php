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
            'acting_for_dean' => ['nullable', 'string', 'max:100', Rule::in(array_merge([''], $usernames))],
            'acting_dean' => ['nullable', 'string', 'max:100', Rule::in(array_merge([''], $usernames))],
            'active_role' => ['required', 'string', Rule::in(array_keys(DocumentSigner::roleLabels()))],
        ]);

        try {
            $this->signerService->save(
                (string) ($data['acting_for_dean'] ?? ''),
                (string) ($data['acting_dean'] ?? ''),
                $data['active_role'],
                $request->user()?->username
            );
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'บันทึกผู้บริหารสำหรับลงนามเอกสารเรียบร้อยแล้ว');
    }
}
