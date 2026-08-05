<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentSigner extends Model
{
    public const ROLE_ACTING_FOR_DEAN = 'acting_for_dean';

    public const ROLE_ACTING_DEAN = 'acting_dean';

    protected $connection = 'eoffice';

    protected $table = 'scireg_document_signers';

    public $timestamps = false;

    protected $fillable = [
        'role_key',
        'username',
        'is_active',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function roleLabels(): array
    {
        return [
            self::ROLE_ACTING_FOR_DEAN => 'ปฏิบัติการแทนคณบดีคณะวิทยาศาสตร์',
            self::ROLE_ACTING_DEAN => 'รักษาการแทนคณบดีคณะวิทยาศาสตร์',
        ];
    }

    public function getRoleLabelAttribute(): string
    {
        return self::roleLabels()[$this->role_key] ?? $this->role_key;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(EofficeUser::class, 'username', 'username');
    }
}
