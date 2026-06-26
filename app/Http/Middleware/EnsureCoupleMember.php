<?php

namespace App\Http\Middleware;

use App\Domains\Couple\Services\CoupleStateService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCoupleMember
{
    public function __construct(
        private readonly CoupleStateService $state,
    ) {
    }

    public function handle(Request $request, Closure $next): Response
    {
        if ($this->state->stateFor($request->user()) === 'none') {
            return redirect()->route('state.couple-required');
        }

        return $next($request);
    }
}
