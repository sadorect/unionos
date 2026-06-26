<?php

namespace App\Actions\CheckIn;

use App\Events\FeatureActivityOccurred;
use App\Domains\CheckIn\Models\MonthlyCoupleCheckIn;
use App\Domains\CheckIn\Services\MonthlyInsightService;
use App\Domains\Couple\Models\Couple;
use App\Models\User;

class SubmitMonthlyCoupleCheckIn
{
    public function __construct(
        private readonly MonthlyInsightService $insights,
    ) {
    }

    public function handle(User $user, Couple $couple, array $attributes): MonthlyCoupleCheckIn
    {
        $checkIn = MonthlyCoupleCheckIn::query()->create([
            'user_id' => $user->getKey(),
            'couple_id' => $couple->getKey(),
            'month' => $this->insights->currentMonth(),
            'felt_heard' => $attributes['felt_heard'],
            'felt_appreciated' => $attributes['felt_appreciated'],
            'conflict_safety' => $attributes['conflict_safety'],
            'emotional_closeness' => $attributes['emotional_closeness'],
            'submitted_at' => now(),
        ]);

        event(new FeatureActivityOccurred('monthly_check_in', 'saved', [
            'status' => 'saved',
        ]));

        return $checkIn;
    }
}
