<?php

namespace App\Domains\Couple\Policies;

use App\Domains\Couple\Enums\CoupleStatus;
use App\Domains\Couple\Models\Couple;
use App\Domains\Couple\Models\CoupleInvite;
use App\Domains\Couple\Services\CoupleAccessService;
use App\Domains\Couple\Services\CoupleStateService;
use App\Models\User;

class CoupleInvitePolicy
{
    public function __construct(
        private readonly CoupleAccessService $access,
        private readonly CoupleStateService $state,
    ) {
    }

    public function create(User $user, Couple $couple): bool
    {
        return $couple->status === CoupleStatus::Pending
            && $this->access->isMember($user, $couple)
            && $this->access->activeMemberCount($couple) < 2;
    }

    public function update(User $user, CoupleInvite $invite): bool
    {
        return $invite->couple->status === CoupleStatus::Pending
            && $this->access->isMember($user, $invite->couple);
    }

    public function delete(User $user, CoupleInvite $invite): bool
    {
        return $this->update($user, $invite);
    }

    public function accept(User $user, CoupleInvite $invite): bool
    {
        return $invite->isUsable()
            && $invite->couple->status === CoupleStatus::Pending
            && $this->state->canCreateCouple($user)
            && $this->access->activeMemberCount($invite->couple) < 2;
    }
}
