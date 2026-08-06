<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LateRegStudent extends Model
{
    protected $connection = 'discipline';

    protected $table = 'late_reg_student';

    public $timestamps = false;

    protected $fillable = [
        'STUDENTID',
        'STUDENTCODE',
        'PREFIXABB',
        'STUDENTNAME',
        'STUDENTSURNAME',
        'PROGRAMNAME',
        'DEPARTMENTNAME',
        'CITIZENID',
        'TERM',
        'YEAR',
        'imported_at',
    ];

    protected function casts(): array
    {
        return [
            'STUDENTID' => 'integer',
            'TERM' => 'integer',
            'YEAR' => 'integer',
            'imported_at' => 'datetime',
        ];
    }

    public function fullName(): string
    {
        $prefix = trim((string) ($this->PREFIXABB ?? ''));
        $first = trim((string) ($this->STUDENTNAME ?? ''));
        $last = trim((string) ($this->STUDENTSURNAME ?? ''));

        return preg_replace('/\s+/u', ' ', trim($prefix.$first.' '.$last)) ?? '';
    }
}
