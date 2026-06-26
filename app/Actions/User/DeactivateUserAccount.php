<?php

namespace App\Actions\User;

use App\Actions\Couple\DissolveCouple;
use App\Domains\Couple\Services\CoupleStateService;
use App\Models\User;

class DeactivateUserAccount
{
    public function __construct(
        private readonly CoupleStateService $coupleState,
        private readonly DissolveCouple $dissolveCouple,
    ) {
    }

    public function handle(User $user): User
    {
        $couple = $this->coupleState->current($user);

        if ($couple !== null && $couple->status->value !== 'dissolved') {
            $this->dissolveCouple->handle($couple);
        }

        $user->delete();

        return $user;
    }
}
