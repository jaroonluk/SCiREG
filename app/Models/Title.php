<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Title extends Model
{
    protected $connection = 'eoffice';

    protected $table = 'tbltitle';

    protected $primaryKey = 'title_id';

    public $timestamps = false;

    protected $fillable = [
        'title_name',
        'title_name_s',
        'title_name_en',
        'title_name_en_s',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(EofficeUser::class, 'title', 'title_id');
    }

    public function getDisplayNameAttribute(): string
    {
        return trim((string) ($this->title_name_s ?: $this->title_name ?: ''));
    }
}
