<?php

namespace App\Http\Middleware;

use App\Domains\Couple\Services\CoupleAccessService;
use App\Domains\Couple\Services\CoupleStateService;
use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class ShareInertiaCommonData
{
    public function __construct(
        private readonly CoupleStateService $state,
        private readonly CoupleAccessService $access,
    ) {
    }

    public function handle(Request $request, Closure $next): Response
    {
        Inertia::share('couple', function () use ($request) {
            $user = $request->user();

            if ($user === null) {
                return [
                    'state' => 'none',
                    'partnerLinked' => false,
                    'hasActiveInvite' => false,
                ];
            }

            $couple = $this->state->current($user);

            if ($couple === null) {
                return [
                    'state' => 'none',
                    'partnerLinked' => false,
                    'hasActiveInvite' => false,
                ];
            }

            return [
                'id' => $couple->id,
                'state' => $couple->status->value,
                'partnerLinked' => $this->access->activeMemberCount($couple) === 2,
                'hasActiveInvite' => $this->access->currentInvite($couple) !== null,
            ];
        });

        return $next($request);
    }
}
