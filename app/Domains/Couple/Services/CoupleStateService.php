<?php

namespace App\Domains\Couple\Services;

use App\Domains\Couple\Enums\CoupleStatus;
use App\Domains\Couple\Models\Couple;
use App\Domains\Couple\Queries\CurrentCoupleForUser;
use App\Models\User;

class CoupleStateService
{
    public function __construct(
        private readonly CurrentCoupleForUser $currentCoupleForUser,
    ) {
    }

    public function current(?User $user): ?Couple
    {
        if ($user === null) {
            return null;
        }

        return $this->currentCoupleForUser->handle($user);
    }

    public function stateFor(?User $user): string
    {
        return $this->current($user)?->status->value ?? 'none';
    }

    public function canCreateCouple(User $user): bool
    {
        return in_array($this->stateFor($user), ['none', CoupleStatus::Dissolved->value], true);
    }

    public function redirectPathFor(User $user): string
    {
        return match ($this->stateFor($user)) {
            CoupleStatus::Pending->value => route('couple.pending', absolute: false),
            CoupleStatus::Active->value => route('couple.show', absolute: false),
            CoupleStatus::Dissolved->value => route('couple.dissolved', absolute: false),
            default => route('couple.create', absolute: false),
        };
    }
}
