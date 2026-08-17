<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormLate extends Model
{
    protected $connection = 'discipline';

    protected $table = 'formlate';

    protected $primaryKey = 'formID';

    public $timestamps = false;

    protected $fillable = [
        'ReasonID',
        'REASON_NAME',
        'STUDENTID',
        'STUDENTCODE',
        'STUDENT_NAME',
        'PROGRAM_NAME',
        'DEPARTMENT_NAME',
        'CLASSID',
        'COURSEID',
        'COURSE_CODE',
        'COURSE_NAME',
        'ROOMID',
        'ROOM_NAME',
        'SEAT_NO',
        'SEMESTER',
        'EXAM_TYPE',
        'ACADYEAR',
        'CREATED_BY',
        'DESCI',
        'LATETIME',
    ];

    protected function casts(): array
    {
        return [
            'ReasonID' => 'integer',
            'STUDENTID' => 'integer',
            'CLASSID' => 'integer',
            'COURSEID' => 'integer',
            'ROOMID' => 'integer',
            'SEMESTER' => 'integer',
            'ACADYEAR' => 'integer',
            'LATETIME' => 'datetime',
        ];
    }

    public function reason(): BelongsTo
    {
        return $this->belongsTo(LateReason::class, 'ReasonID', 'ReasonID');
    }
}
