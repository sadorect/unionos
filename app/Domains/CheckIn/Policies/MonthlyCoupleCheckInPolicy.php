<?php

namespace App\Domains\CheckIn\Policies;

use App\Domains\CheckIn\Models\MonthlyCoupleCheckIn;
use App\Domains\CheckIn\Services\MonthlyInsightService;
use App\Models\User;

class MonthlyCoupleCheckInPolicy
{
    public function __construct(
        private readonly MonthlyInsightService $insights,
    ) {
    }

    public function viewAny(User $user): bool
    {
        return $this->insights->activeCoupleFor($user) !== null;
    }

    public function create(User $user): bool
    {
        return $this->viewAny($user);
    }

    public function view(User $user, MonthlyCoupleCheckIn $checkIn): bool
    {
        return $user->is($checkIn->user);
    }

    public function viewInsight(User $user): bool
    {
        return $this->insights->activeCoupleFor($user) !== null;
    }
}
