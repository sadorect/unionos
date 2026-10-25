<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\QueryException;
use Illuminate\Database\ConnectionException;
use App\Support\Observability\PrivacySafeLogger;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Inertia\Inertia;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withSchedule(function (Schedule $schedule): void {
        $schedule->command('unionos:advance-system-state')->hourly();
        $schedule->command('unionos:dispatch-notifications')->hourly();
    })
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(prepend: [
            \App\Http\Middleware\AddSecurityHeaders::class,
        ]);

        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \App\Http\Middleware\ShareInertiaCommonData::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

        $middleware->alias([
            'couple.member' => \App\Http\Middleware\EnsureCoupleMember::class,
            'couple.active' => \App\Http\Middleware\EnsureActiveCouple::class,
            'system.token' => \App\Http\Middleware\RequireSystemToken::class,
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (AuthorizationException $exception, $request) {
            app(PrivacySafeLogger::class)->feature('unionos.policy_violation', [
                'feature' => 'policy',
                'action' => 'denied',
                'status' => 'denied',
                'method' => $request->method(),
                'route' => optional($request->route())->getName(),
            ]);

            if ($request->isMethod('GET') && (! $request->expectsJson() || $request->header('X-Inertia'))) {
                return Inertia::render('State/NotAvailable')->toResponse($request)->setStatusCode(403);
            }

            return null;
        });

        $exceptions->render(function (NotFoundHttpException $exception, $request) {
            if ($request->isMethod('GET') && (! $request->expectsJson() || $request->header('X-Inertia'))) {
                return Inertia::render('State/NotAvailable')->toResponse($request)->setStatusCode(404);
            }

            return null;
        });

        $exceptions->render(function (QueryException|ConnectionException|\PDOException $exception, $request) {
            app(PrivacySafeLogger::class)->alert('unionos.operational_failure', [
                'feature' => 'application',
                'action' => 'exception',
                'status' => 'failed',
                'method' => $request->method(),
                'route' => optional($request->route())->getName(),
                'exception' => $exception::class,
            ]);

            if ($request->isMethod('GET') && (! $request->expectsJson() || $request->header('X-Inertia'))) {
                return Inertia::render('State/LoadFailed', [
                    'retryUrl' => url()->current(),
                ])->toResponse($request)->setStatusCode(503);
            }

            return back()
                ->withInput()
                ->with('error', 'We couldn\'t complete that action.')
                ->with('retryableError', [
                    'message' => 'Your changes may not have been saved. Please review and try again.',
                    'retryUrl' => url()->previous(),
                ]);
        });
    })->create();
