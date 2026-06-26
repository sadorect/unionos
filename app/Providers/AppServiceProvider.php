<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (app()->isProduction() && config('app.debug')) {
            throw new \RuntimeException('APP_DEBUG must be false in production.');
        }

        Vite::prefetch(concurrency: 3);

        RateLimiter::for('auth-sensitive', function (Request $request) {
            return Limit::perMinute((int) config('unionos.rate_limits.auth_sensitive'))
                ->by($request->input('email') . '|' . $request->ip());
        });
    }
}
