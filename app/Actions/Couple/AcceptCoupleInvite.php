<?php

namespace App\Actions\Couple;

use App\Domains\Couple\Enums\CoupleStatus;
use App\Domains\Couple\Models\Couple;
use App\Domains\Couple\Models\CoupleInvite;
use App\Domains\Couple\Models\CoupleMembership;
use App\Domains\Couple\Services\CoupleAccessService;
use App\Domains\Couple\Services\CoupleStateService;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AcceptCoupleInvite
{
    public function __construct(
        private readonly CoupleAccessService $access,
        private readonly CoupleStateService $state,
    ) {
    }

    public function handle(User $user, CoupleInvite $invite): Couple
    {
        if (! $invite->isUsable()) {
            throw ValidationException::withMessages([
                'invite' => 'This invitation is no longer available.',
            ]);
        }

        if (! $this->state->canCreateCouple($user)) {
            throw ValidationException::withMessages([
                'invite' => 'Your account is already linked to another couple space.',
            ]);
        }

        return DB::transaction(function () use ($user, $invite) {
            $lockedInvite = CoupleInvite::query()
                ->whereKey($invite->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            $couple = Couple::query()
                ->whereKey($lockedInvite->couple_id)
                ->lockForUpdate()
                ->firstOrFail();

            if (! $lockedInvite->isUsable() || $couple->status !== CoupleStatus::Pending) {
                throw ValidationException::withMessages([
                    'invite' => 'This invitation is no longer available.',
                ]);
            }

            $slot = $this->access->nextOpenSlot($couple);

            if ($slot === null) {
                throw ValidationException::withMessages([
                    'invite' => 'This couple space is already full.',
                ]);
            }

            CoupleMembership::query()->create([
                'couple_id' => $couple->getKey(),
                'user_id' => $user->getKey(),
                'slot' => $slot,
                'joined_at' => now(),
                'is_active' => true,
            ]);

            $lockedInvite->forceFill([
                'used_at' => now(),
            ])->save();

            $couple->forceFill([
                'status' => CoupleStatus::Active,
            ])->save();

            return $couple->fresh();
        });
    }
}
