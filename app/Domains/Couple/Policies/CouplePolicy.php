<?php

namespace App\Domains\Couple\Policies;

use App\Domains\Couple\Enums\CoupleStatus;
use App\Domains\Couple\Models\Couple;
use App\Domains\Couple\Services\CoupleAccessService;
use App\Domains\Couple\Services\CoupleStateService;
use App\Models\User;

class CouplePolicy
{
    public function __construct(
        private readonly CoupleAccessService $access,
        private readonly CoupleStateService $state,
    ) {
    }

    public function create(User $user): bool
    {
        return $this->state->canCreateCouple($user);
    }

    public function view(User $user, Couple $couple): bool
    {
        return $this->access->isMember($user, $couple);
    }

    public function dissolve(User $user, Couple $couple): bool
    {
        return $this->access->isMember($user, $couple)
            && $couple->status !== CoupleStatus::Dissolved;
    }
}
