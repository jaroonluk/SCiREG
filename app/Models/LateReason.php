<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LateReason extends Model
{
    protected $connection = 'discipline';

    protected $table = 'reason';

    protected $primaryKey = 'ReasonID';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'ReasonID',
        'ReasonName',
        'ReasonDesc',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'ReasonID' => 'integer',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function forms(): HasMany
    {
        return $this->hasMany(FormLate::class, 'ReasonID', 'ReasonID');
    }
}
