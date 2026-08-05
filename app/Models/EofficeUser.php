<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;

class EofficeUser extends Authenticatable
{
    public const PD_LEVEL_RETIRED = '4';

    public const PD_LEVEL_RESIGNED = '5';

    protected $connection = 'eoffice';

    protected $table = 'tbluser';

    protected $primaryKey = 'username';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'username',
        'email',
        'title',
        'fname',
        'lname',
    ];

    protected $hidden = [
        'password',
    ];

    protected $with = [
        'academicTitle',
    ];

    public function academicTitle(): BelongsTo
    {
        return $this->belongsTo(Title::class, 'title', 'title_id');
    }

    public function sciregPrivilege(): HasOne
    {
        return $this->hasOne(Privilege::class, 'username', 'username')
            ->where('system_id', Privilege::SYSTEM_SCIREG)
            ->where('level', Privilege::LEVEL_ACCESS);
    }

    public function scopeActiveEmployment(Builder $query): Builder
    {
        return $query->where(function (Builder $inner) {
            $inner->whereNull('pd_level')
                ->orWhere('pd_level', '')
                ->orWhereNotIn('pd_level', [
                    self::PD_LEVEL_RETIRED,
                    self::PD_LEVEL_RESIGNED,
                ]);
        });
    }

    public function scopeAssignableForPermissions(Builder $query): Builder
    {
        return $query->activeEmployment()
            ->whereRaw('LOWER(username) NOT LIKE ?', ['%test%'])
            ->whereRaw('LOWER(username) NOT LIKE ?', ['%webmaster%']);
    }

    public function isActiveEmployment(): bool
    {
        return ! in_array((string) $this->pd_level, [
            self::PD_LEVEL_RETIRED,
            self::PD_LEVEL_RESIGNED,
        ], true);
    }

    public function hasSciregAccess(): bool
    {
        return $this->sciregPrivilege()->exists();
    }

    public function getHasSciregAccessAttribute(): bool
    {
        return $this->relationLoaded('sciregPrivilege')
            ? $this->sciregPrivilege !== null
            : $this->hasSciregAccess();
    }

    public function getAuthPasswordName(): string
    {
        return 'password';
    }

    public function getRememberTokenName(): ?string
    {
        return null;
    }

    public function getTitleLabelAttribute(): string
    {
        return $this->academicTitle?->display_name ?? '';
    }

    public function getFullNameAttribute(): string
    {
        return preg_replace('/\s+/u', ' ', trim($this->title_label.' '.$this->fname.' '.$this->lname)) ?? '';
    }
}
