<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Privilege extends Model
{
    public const SYSTEM_SCIREG = 23;

    /** เจ้าหน้าที่งานบริการ — ใช้ได้ทุกเมนู */
    public const LEVEL_SERVICE = 1;

    /** เจ้าหน้าที่สาขาวิชา — รายงานค่าธรรมเนียมของสาขาตนเองเท่านั้น */
    public const LEVEL_DEPARTMENT = 2;

    /** เจ้าหน้าที่การเงิน — จัดการชำระเงินค่าธรรมเนียมวิจัยเท่านั้น */
    public const LEVEL_FINANCE = 3;

    /** @deprecated Use LEVEL_SERVICE */
    public const LEVEL_ACCESS = self::LEVEL_SERVICE;

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

    /**
     * @return list<int>
     */
    public static function accessLevels(): array
    {
        return [
            self::LEVEL_SERVICE,
            self::LEVEL_DEPARTMENT,
            self::LEVEL_FINANCE,
        ];
    }

    /**
     * @return array<int, string>
     */
    public static function levelLabels(): array
    {
        return [
            self::LEVEL_SERVICE => 'เจ้าหน้าที่งานบริการ',
            self::LEVEL_DEPARTMENT => 'เจ้าหน้าที่สาขาวิชา',
            self::LEVEL_FINANCE => 'เจ้าหน้าที่การเงิน',
        ];
    }

    public static function levelLabel(int $level): string
    {
        return self::levelLabels()[$level] ?? 'ไม่ระบุสิทธิ';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(EofficeUser::class, 'username', 'username');
    }

    public function scopeForScireg($query)
    {
        return $query->where('system_id', self::SYSTEM_SCIREG)
            ->whereIn('level', self::accessLevels());
    }

    public function getLevelLabelAttribute(): string
    {
        return self::levelLabel((int) $this->level);
    }
}
