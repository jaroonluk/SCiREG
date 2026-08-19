<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSciregRole
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next, string ...$levels): Response
    {
        $user = $request->user();

        if (! $user || ! $user->hasSciregAccess()) {
            abort(403, 'ไม่มีสิทธิเข้าใช้งานระบบ');
        }

        $user->loadMissing('sciregPrivilege');

        if ($user->isSciregAdmin()) {
            return $next($request);
        }

        if ($levels === []) {
            return $next($request);
        }

        $allowed = array_map('intval', $levels);
        $current = (int) $user->sciregLevel();

        if (! in_array($current, $allowed, true)) {
            abort(403, 'ไม่มีสิทธิเข้าใช้งานเมนูนี้');
        }

        return $next($request);
    }
}
