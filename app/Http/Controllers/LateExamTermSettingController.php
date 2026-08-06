<?php

namespace App\Http\Controllers;

use App\Services\LateExamTermSettingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LateExamTermSettingController extends Controller
{
    public function __construct(
        private readonly LateExamTermSettingService $settings,
    ) {}

    public function index(): View
    {
        $current = $this->settings->current();
        $maxYear = (int) date('Y') + 543 + 1;

        return view('late-exam.term-setting', [
            'term' => $current['term'],
            'year' => $current['year'],
            'examType' => $current['exam_type'],
            'updatedBy' => $current['updated_by'],
            'updatedAt' => $current['updated_at'],
            'years' => range($maxYear, 2555),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'term' => ['required', 'integer', 'in:1,2'],
            'year' => ['required', 'integer', 'min:2555', 'max:2600'],
            'exam_type' => ['required', 'string', 'in:M,F'],
        ], [
            'term.required' => 'กรุณาเลือกภาคการศึกษา',
            'year.required' => 'กรุณาเลือกปีการศึกษา',
            'exam_type.required' => 'กรุณาเลือกช่วงสอบ',
        ]);

        $this->settings->update(
            (int) $data['term'],
            (int) $data['year'],
            (string) $data['exam_type'],
            $request->user()?->username
        );

        $termLabel = (int) $data['term'] === 1 ? 'ภาคต้น' : 'ภาคปลาย';
        $examLabel = $data['exam_type'] === 'M' ? 'กลางภาค' : 'ปลายภาค';

        return redirect()
            ->route('late-exam.term-setting')
            ->with('success', "บันทึกแล้ว: {$termLabel} ปีการศึกษา {$data['year']} · {$examLabel}");
    }
}
