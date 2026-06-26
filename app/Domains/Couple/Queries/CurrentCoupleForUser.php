<?php

namespace App\Domains\Couple\Queries;

use App\Domains\Couple\Models\Couple;
use App\Models\User;

class CurrentCoupleForUser
{
    public function handle(User $user): ?Couple
    {
        return Couple::query()
            ->select('couples.*')
            ->join('couple_memberships', 'couple_memberships.couple_id', '=', 'couples.id')
            ->where('couple_memberships.user_id', $user->getKey())
            ->orderByDesc('couple_memberships.is_active')
            ->orderByDesc('couple_memberships.joined_at')
            ->first();
    }
}
