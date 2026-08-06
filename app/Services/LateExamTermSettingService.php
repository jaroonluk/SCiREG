<?php

namespace App\Services;

use App\Models\LateExamTermSetting;

class LateExamTermSettingService
{
    public const SETTING_ID = 1;

    /**
     * @return array{
     *   term:int,
     *   year:int,
     *   exam_type:string,
     *   exam_type_label:string,
     *   updated_by:?string,
     *   updated_at:?\Illuminate\Support\Carbon,
     *   exists:bool,
     *   term_label:string
     * }
     */
    public function current(): array
    {
        $row = LateExamTermSetting::query()->find(self::SETTING_ID);

        if ($row) {
            $examType = strtoupper((string) ($row->EXAM_TYPE ?: 'F'));
            if (! in_array($examType, ['M', 'F'], true)) {
                $examType = 'F';
            }

            return [
                'term' => (int) $row->TERM,
                'year' => (int) $row->ACADYEAR,
                'exam_type' => $examType,
                'exam_type_label' => $row->examTypeLabel(),
                'updated_by' => $row->updated_by,
                'updated_at' => $row->updated_at,
                'exists' => true,
                'term_label' => $row->termLabel(),
            ];
        }

        $month = (int) date('n');
        $term = ($month >= 6 && $month <= 10) ? 1 : 2;

        return [
            'term' => $term,
            'year' => (int) date('Y') + 543,
            'exam_type' => 'F',
            'exam_type_label' => 'ปลายภาค',
            'updated_by' => null,
            'updated_at' => null,
            'exists' => false,
            'term_label' => $term === 1 ? 'ภาคต้น' : 'ภาคปลาย',
        ];
    }

    public function currentTerm(): int
    {
        return $this->current()['term'];
    }

    public function currentYear(): int
    {
        return $this->current()['year'];
    }

    public function currentExamType(): string
    {
        return $this->current()['exam_type'];
    }

    public function update(int $term, int $year, string $examType, ?string $updatedBy = null): LateExamTermSetting
    {
        $examType = strtoupper($examType);
        if (! in_array($examType, ['M', 'F'], true)) {
            $examType = 'F';
        }

        return LateExamTermSetting::query()->updateOrCreate(
            ['id' => self::SETTING_ID],
            [
                'TERM' => $term,
                'ACADYEAR' => $year,
                'EXAM_TYPE' => $examType,
                'updated_by' => $updatedBy,
                'updated_at' => now(),
            ]
        );
    }
}
