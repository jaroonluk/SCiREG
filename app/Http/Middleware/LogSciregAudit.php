<?php

namespace App\Http\Middleware;

use App\Services\AuditLogService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogSciregAudit
{
    public function __construct(
        private readonly AuditLogService $auditLog,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! auth()->check()) {
            return $response;
        }

        $method = strtoupper($request->method());
        $routeName = $request->route()?->getName() ?? '';

        $alwaysLogGet = [
            'late-exam.print.show',
            'late-exam.summary.export',
            'research-fee.summary.export',
            'research-fee.notice.student',
            'research-fee.notice.sponsor',
            'research-fee.payments.report',
            'audit-logs.index',
        ];

        $shouldLog = in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'], true)
            || in_array($routeName, $alwaysLogGet, true);

        if ($shouldLog && $response->getStatusCode() < 500) {
            $this->auditLog->logFromRequest($request, $response->getStatusCode());
        }

        return $response;
    }
}
