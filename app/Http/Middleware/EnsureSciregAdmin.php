<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSciregAdmin
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->isSciregAdmin()) {
            abort(403, 'ไม่มีสิทธิเข้าใช้งานเมนูนี้');
        }

        return $next($request);
    }
}
