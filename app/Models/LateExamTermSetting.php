<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LateExamTermSetting extends Model
{
    protected $connection = 'discipline';

    protected $table = 'late_exam_term_setting';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'TERM',
        'ACADYEAR',
        'EXAM_TYPE',
        'updated_by',
        'updated_at',
    ];

    protected function casts(): array
    {
        return [
            'TERM' => 'integer',
            'ACADYEAR' => 'integer',
            'updated_at' => 'datetime',
        ];
    }

    public function termLabel(): string
    {
        return match ($this->TERM) {
            1 => 'ภาคต้น',
            2 => 'ภาคปลาย',
            default => (string) $this->TERM,
        };
    }

    public function examTypeLabel(): string
    {
        return match (strtoupper((string) $this->EXAM_TYPE)) {
            'M' => 'กลางภาค',
            'F' => 'ปลายภาค',
            default => (string) $this->EXAM_TYPE,
        };
    }
}
