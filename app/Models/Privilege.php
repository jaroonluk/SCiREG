<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Privilege extends Model
{
    public const SYSTEM_SCIREG = 23;

    public const LEVEL_ACCESS = 1;

    protected $connection = 'eoffice';

    protected $table = 'tblprivileges';

    protected $primaryKey = 'privilegs_id';

    public $timestamps = false;

    protected $fillable = [
        'system_id',
        'username',
        'level',
    ];

    protected function casts(): array
    {
        return [
            'system_id' => 'integer',
            'level' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(EofficeUser::class, 'username', 'username');
    }

    public function scopeForScireg($query)
    {
        return $query->where('system_id', self::SYSTEM_SCIREG)
            ->where('level', self::LEVEL_ACCESS);
    }
}
