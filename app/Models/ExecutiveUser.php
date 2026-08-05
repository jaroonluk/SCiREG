<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExecutiveUser extends Model
{
    protected $connection = 'eoffice';

    protected $table = 'tbluser_ex';

    public $timestamps = false;

    protected $fillable = [
        'extID',
        'username',
        'position',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(EofficeUser::class, 'username', 'username');
    }
}
