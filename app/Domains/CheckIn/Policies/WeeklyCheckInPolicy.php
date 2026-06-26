<?php

namespace App\Domains\CheckIn\Policies;

use App\Domains\CheckIn\Models\WeeklyCheckIn;
use App\Domains\CheckIn\Services\WeeklyCheckInService;
use App\Domains\Couple\Enums\CoupleStatus;
use App\Domains\Couple\Services\CoupleStateService;
use App\Models\User;

class WeeklyCheckInPolicy
{
    public function __construct(
        private readonly CoupleStateService $state,
        private readonly WeeklyCheckInService $checkIns,
    ) {
    }

    public function viewAny(User $user): bool
    {
        return $this->state->current($user)?->status === CoupleStatus::Active;
    }

    public function create(User $user): bool
    {
        return $this->viewAny($user);
    }

    public function view(User $user, WeeklyCheckIn $checkIn): bool
    {
        return $user->is($checkIn->user);
    }

    public function update(User $user, WeeklyCheckIn $checkIn): bool
    {
        return $user->is($checkIn->user)
            && $this->state->current($user)?->is($checkIn->couple)
            && $checkIn->couple->status === CoupleStatus::Active
            && $this->checkIns->isEditable($checkIn);
    }

    public function delete(User $user, WeeklyCheckIn $checkIn): bool
    {
        return false;
    }
}
