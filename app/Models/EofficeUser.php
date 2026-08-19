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

    /**
     * Map tbluser.department_id → depart_fee_research.depart_id
     *
     * @var array<int, int>
     */
    public const RESEARCH_FEE_DEPARTMENT_MAP = [
        10 => 1, // คณิตศาสตร์
        6 => 2,  // เคมี
        9 => 3,  // จุลชีววิทยา
        11 => 4, // ชีวเคมี
        8 => 5,  // ชีววิทยา
        7 => 6,  // ฟิสิกส์
        4 => 7,  // วิทยาการคอมพิวเตอร์
        12 => 8, // วิทยาศาสตร์สิ่งแวดล้อม
        5 => 9,  // สถิติ
    ];

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
        'department_id',
    ];

    protected $hidden = [
        'password',
    ];

    protected $with = [
        'academicTitle',
    ];

    protected function casts(): array
    {
        return [
            'department_id' => 'integer',
        ];
    }

    public function academicTitle(): BelongsTo
    {
        return $this->belongsTo(Title::class, 'title', 'title_id');
    }

    public function sciregPrivilege(): HasOne
    {
        return $this->hasOne(Privilege::class, 'username', 'username')
            ->where('system_id', Privilege::SYSTEM_SCIREG)
            ->whereIn('level', Privilege::accessLevels());
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

    public function isSciregAdmin(): bool
    {
        $email = strtolower(trim((string) ($this->email ?? '')));
        if ($email === '') {
            return false;
        }

        return in_array($email, config('scireg.admin_emails', []), true);
    }

    public function canViewAuditLogs(): bool
    {
        return $this->isSciregAdmin();
    }

    public function hasSciregAccess(): bool
    {
        return $this->isSciregAdmin() || $this->sciregPrivilege()->exists();
    }

    public function getHasSciregAccessAttribute(): bool
    {
        return $this->hasSciregAccess();
    }

    public function sciregLevel(): ?int
    {
        $privilege = $this->relationLoaded('sciregPrivilege')
            ? $this->sciregPrivilege
            : $this->sciregPrivilege()->first();

        return $privilege?->level;
    }

    public function sciregRoleLabel(): string
    {
        if ($this->isSciregAdmin()) {
            return 'ผู้ดูแลระบบ';
        }

        $level = $this->sciregLevel();

        return $level ? Privilege::levelLabel($level) : 'ไม่มีสิทธิ';
    }

    public function isServiceOfficer(): bool
    {
        return $this->isSciregAdmin() || $this->sciregLevel() === Privilege::LEVEL_SERVICE;
    }

    public function isDepartmentOfficer(): bool
    {
        return ! $this->isSciregAdmin() && $this->sciregLevel() === Privilege::LEVEL_DEPARTMENT;
    }

    public function isFinanceOfficer(): bool
    {
        return ! $this->isSciregAdmin() && $this->sciregLevel() === Privilege::LEVEL_FINANCE;
    }

    public function canManageUsers(): bool
    {
        return $this->isServiceOfficer();
    }

    public function canAccessPayments(): bool
    {
        return $this->isSciregAdmin() || in_array($this->sciregLevel(), [
            Privilege::LEVEL_SERVICE,
            Privilege::LEVEL_FINANCE,
        ], true);
    }

    public function canAccessImport(): bool
    {
        return $this->isServiceOfficer();
    }

    public function canAccessSummaryReport(): bool
    {
        return $this->isSciregAdmin() || in_array($this->sciregLevel(), [
            Privilege::LEVEL_SERVICE,
            Privilege::LEVEL_DEPARTMENT,
        ], true);
    }

    public function canAccessLateExam(): bool
    {
        return $this->isServiceOfficer();
    }

    public function researchFeeDepartId(): ?int
    {
        $departmentId = (int) ($this->department_id ?? 0);
        if ($departmentId <= 0) {
            return null;
        }

        return self::RESEARCH_FEE_DEPARTMENT_MAP[$departmentId] ?? null;
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
