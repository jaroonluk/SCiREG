<?php

namespace App\Services;

use App\Models\AuditLogScireg;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class AuditLogService
{
    /**
     * Explicit write for custom events (login, logout, etc.).
     *
     * @param  array<string, mixed>|null  $requestData
     */
    public function write(
        string $module,
        string $action,
        ?string $description = null,
        ?array $requestData = null,
        ?int $statusCode = null,
        ?Request $request = null,
    ): void {
        try {
            $request ??= request();
            $user = Auth::user();

            AuditLogScireg::query()->create([
                'username' => $user?->username ?? ($requestData['username'] ?? null),
                'user_name' => $user?->full_name
                    ?? ($requestData['user_name'] ?? null),
                'module' => $module,
                'action' => $action,
                'description' => $description,
                'route_name' => $request->route()?->getName(),
                'method' => strtoupper($request->method()),
                'url' => mb_substr($request->fullUrl(), 0, 500),
                'ip_address' => $request->ip(),
                'user_agent' => mb_substr((string) $request->userAgent(), 0, 500),
                'request_data' => $requestData,
                'status_code' => $statusCode,
                'created_at' => now(),
            ]);
        } catch (Throwable) {
            // Never break the main request because of audit logging.
        }
    }

    public function logFromRequest(Request $request, int $statusCode): void
    {
        $routeName = $request->route()?->getName() ?? '';
        [$module, $action, $description] = $this->describeRoute($routeName, $request);

        if ($module === null) {
            return;
        }

        $this->write(
            module: $module,
            action: $action,
            description: $description,
            requestData: $this->safePayload($request),
            statusCode: $statusCode,
            request: $request,
        );
    }

    /**
     * @return array{0:?string,1:string,2:string}
     */
    private function describeRoute(string $routeName, Request $request): array
    {
        $map = [
            'logout' => ['auth', 'logout', 'ออกจากระบบ'],
            'research-fee.payments.update' => ['research_fee', 'payment.update', 'บันทึก/แก้ไขสถานะชำระค่าธรรมเนียมวิจัย'],
            'research-fee.payments.report' => ['research_fee', 'payment.report', 'ดูรายงานการชำระค่าธรรมเนียมวิจัย'],
            'research-fee.notice.student' => ['research_fee', 'notice.student', 'ดาวน์โหลดหนังสือถึงนักศึกษา'],
            'research-fee.notice.sponsor' => ['research_fee', 'notice.sponsor', 'ดาวน์โหลดหนังสือถึงต้นสังกัด'],
            'research-fee.import.store' => ['research_fee', 'import.store', 'นำเข้าข้อมูลค่าธรรมเนียมวิจัยจาก REG'],
            'research-fee.summary' => ['research_fee', 'summary.view', 'ดูรายงานสรุปค่าธรรมเนียมวิจัย'],
            'research-fee.summary.export' => ['research_fee', 'summary.export', 'ส่งออก Excel รายงานค่าธรรมเนียมวิจัย'],
            'users.permissions.grant' => ['users', 'permission.grant', 'กำหนดสิทธิผู้ใช้งานระบบ'],
            'users.permissions.revoke' => ['users', 'permission.revoke', 'เพิกถอนสิทธิผู้ใช้งานระบบ'],
            'late-exam.import.store' => ['late_exam', 'import.store', 'นำเข้าข้อมูลนักศึกษาสอบช้าจาก REG'],
            'late-exam.record.store' => ['late_exam', 'record.store', 'บันทึกการเข้าสอบช้า'],
            'late-exam.print.show' => ['late_exam', 'print.show', 'พิมพ์แบบฟอร์มการเข้าสอบช้า'],
            'late-exam.print.destroy' => ['late_exam', 'print.destroy', 'ลบรายการผู้เข้าสอบช้า'],
            'late-exam.signers.update' => ['late_exam', 'signers.update', 'กำหนดผู้บริหารลงนามเอกสาร'],
            'late-exam.summary' => ['late_exam', 'summary.view', 'ดูรายงานสรุปการเข้าสอบช้า'],
            'late-exam.summary.export' => ['late_exam', 'summary.export', 'ส่งออก Excel รายงานเข้าสอบช้า'],
            'late-exam.term-setting.update' => ['late_exam', 'term.update', 'กำหนดภาคการศึกษาปัจจุบัน'],
            'audit-logs.index' => ['system', 'audit.view', 'เข้าดูข้อมูล audit log'],
        ];

        if (isset($map[$routeName])) {
            return $map[$routeName];
        }

        // Fallback: log mutating methods on named scireg routes.
        if (in_array(strtoupper($request->method()), ['POST', 'PUT', 'PATCH', 'DELETE'], true) && $routeName !== '') {
            return ['system', $routeName, 'ดำเนินการ '.$routeName];
        }

        return [null, '', ''];
    }

    /**
     * @return array<string, mixed>|null
     */
    private function safePayload(Request $request): ?array
    {
        $hidden = ['_token', 'password', 'password_confirmation', 'remember'];
        $data = collect($request->except($hidden))
            ->map(function ($value) {
                if (is_string($value) && mb_strlen($value) > 200) {
                    return mb_substr($value, 0, 200).'…';
                }

                return $value;
            })
            ->all();

        return $data === [] ? null : $data;
    }
}
