<?php

namespace App\Http\Middleware;

use App\Domains\Couple\Enums\CoupleStatus;
use App\Domains\Couple\Services\CoupleStateService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureActiveCouple
{
    public function __construct(
        private readonly CoupleStateService $state,
    ) {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $state = $this->state->stateFor($request->user());

        if ($state === CoupleStatus::Active->value) {
            return $next($request);
        }

        if ($state === 'none') {
            return redirect()->route('state.couple-required');
        }

        return redirect()->route($state === CoupleStatus::Dissolved->value
            ? 'state.couple-dissolved'
            : 'state.couple-pending');
    }
}
