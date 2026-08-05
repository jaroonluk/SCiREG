<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeeResearch extends Model
{
    protected $connection = 'eoffice';

    protected $table = 'fee_research';

    public $timestamps = false;

    public $incrementing = false;

    protected $primaryKey = null;

    protected $fillable = [
        'std_code',
        'name',
        'level',
        'couse',
        'depart_id',
        'status',
        'amount',
        'term',
        'year',
        'slip_no',
    ];

    protected function casts(): array
    {
        return [
            'depart_id' => 'integer',
            'amount' => 'float',
        ];
    }
}
