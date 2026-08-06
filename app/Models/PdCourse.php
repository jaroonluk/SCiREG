<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PdCourse extends Model
{
    protected $connection = 'eoffice';

    protected $table = 'pdcourse';

    public $timestamps = false;

    protected $fillable = [
        'subjcode',
        'subjname',
        'credit',
        'lec',
        'lab',
        'other',
        'courseint',
    ];
}
