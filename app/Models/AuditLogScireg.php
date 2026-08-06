<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLogScireg extends Model
{
    protected $connection = 'discipline';

    protected $table = 'audit_log_scireg';

    public $timestamps = false;

    protected $fillable = [
        'username',
        'user_name',
        'module',
        'action',
        'description',
        'route_name',
        'method',
        'url',
        'ip_address',
        'user_agent',
        'request_data',
        'status_code',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'request_data' => 'array',
            'status_code' => 'integer',
            'created_at' => 'datetime',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function moduleLabels(): array
    {
        return [
            'auth' => 'เข้าสู่ระบบ',
            'late_exam' => 'เข้าสอบช้า',
            'research_fee' => 'ค่าธรรมเนียมวิจัย',
            'users' => 'สิทธิผู้ใช้',
            'system' => 'ระบบ',
        ];
    }

    public function moduleLabel(): string
    {
        return self::moduleLabels()[$this->module] ?? $this->module;
    }
}
