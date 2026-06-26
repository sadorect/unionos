<?php

namespace App\Actions\CheckIn;

use App\Events\FeatureActivityOccurred;
use App\Domains\CheckIn\Models\WeeklyCheckIn;
use App\Domains\CheckIn\Services\WeeklyCheckInService;
use App\Domains\Couple\Models\Couple;
use App\Models\User;

class UpsertWeeklyCheckIn
{
    public function __construct(
        private readonly WeeklyCheckInService $checkIns,
    ) {
    }

    public function handle(User $user, Couple $couple, array $attributes): WeeklyCheckIn
    {
        $checkIn = WeeklyCheckIn::query()
            ->where('user_id', $user->getKey())
            ->whereDate('week_start_date', $this->checkIns->currentWeekStart()->toDateString())
            ->first()
            ?? new WeeklyCheckIn([
                'user_id' => $user->getKey(),
                'week_start_date' => $this->checkIns->currentWeekStart(),
            ]);

        $checkIn->fill([
            'couple_id' => $couple->getKey(),
            'emotional_availability' => $attributes['emotional_availability'],
            'communication_effort' => $attributes['communication_effort'],
            'stress_level' => $attributes['stress_level'],
            'appreciation_text' => $attributes['appreciation_text'] ?? null,
            'struggle_text' => $attributes['struggle_text'] ?? null,
            'intention_text' => $attributes['intention_text'] ?? null,
        ])->save();

        event(new FeatureActivityOccurred('weekly_check_in', 'saved', [
            'status' => 'saved',
        ]));

        return $checkIn->refresh();
    }
}
