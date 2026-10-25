<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->is_admin) {
            if ($request->isMethod('GET') && (! $request->expectsJson() || $request->header('X-Inertia'))) {
                return Inertia::render('State/NotAvailable')->toResponse($request)->setStatusCode(403);
            }

            abort(403);
        }

        return $next($request);
    }
}
