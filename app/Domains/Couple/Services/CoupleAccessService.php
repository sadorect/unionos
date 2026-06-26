<?php

namespace App\Domains\Couple\Services;

use App\Domains\Couple\Models\Couple;
use App\Domains\Couple\Models\CoupleInvite;
use App\Models\User;

class CoupleAccessService
{
    public function isMember(User $user, Couple $couple): bool
    {
        return $couple->memberships()
            ->where('user_id', $user->getKey())
            ->exists();
    }

    public function activeMemberCount(Couple $couple): int
    {
        return $couple->memberships()
            ->where('is_active', true)
            ->count();
    }

    public function nextOpenSlot(Couple $couple): ?int
    {
        $usedSlots = $couple->memberships()
            ->where('is_active', true)
            ->pluck('slot')
            ->all();

        foreach ([1, 2] as $slot) {
            if (! in_array($slot, $usedSlots, true)) {
                return $slot;
            }
        }

        return null;
    }

    public function currentInvite(Couple $couple): ?CoupleInvite
    {
        return $couple->invites()
            ->valid()
            ->latest('id')
            ->first();
    }
}
