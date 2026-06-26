<?php

namespace App\Actions\Couple;

use App\Domains\Couple\Enums\CoupleStatus;
use App\Domains\Couple\Models\Couple;
use App\Domains\Couple\Models\CoupleMembership;
use App\Domains\Couple\Services\CoupleStateService;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CreateCouple
{
    public function __construct(
        private readonly CoupleStateService $state,
    ) {
    }

    public function handle(User $user): Couple
    {
        if (! $this->state->canCreateCouple($user)) {
            throw ValidationException::withMessages([
                'couple' => 'You already have a current couple space.',
            ]);
        }

        return DB::transaction(function () use ($user) {
            $couple = Couple::query()->create([
                'status' => CoupleStatus::Pending,
            ]);

            CoupleMembership::query()->create([
                'couple_id' => $couple->getKey(),
                'user_id' => $user->getKey(),
                'slot' => 1,
                'joined_at' => now(),
                'is_active' => true,
            ]);

            return $couple;
        });
    }
}
