<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireSystemToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $configured = config('unionos.system_token');

        if (empty($configured)) {
            abort(503, 'System endpoint not configured.');
        }

        $provided = $request->bearerToken()
            ?? $request->header('X-System-Token');

        if (! hash_equals($configured, (string) $provided)) {
            abort(401, 'Unauthorized.');
        }

        return $next($request);
    }
}
