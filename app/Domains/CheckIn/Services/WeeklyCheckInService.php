<?php

namespace App\Domains\CheckIn\Services;

use App\Domains\CheckIn\Models\WeeklyCheckIn;
use App\Domains\Couple\Enums\CoupleStatus;
use App\Domains\Couple\Models\Couple;
use App\Domains\Couple\Services\CoupleStateService;
use App\Models\User;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class WeeklyCheckInService
{
    public function __construct(
        private readonly CoupleStateService $state,
    ) {
    }

    public function activeCoupleFor(User $user): ?Couple
    {
        $couple = $this->state->current($user);

        if ($couple?->status !== CoupleStatus::Active) {
            return null;
        }

        return $couple;
    }

    public function currentWeekStart(?CarbonInterface $now = null): CarbonImmutable
    {
        return CarbonImmutable::instance(($now ?? now())->startOfWeek(CarbonInterface::MONDAY))->startOfDay();
    }

    public function currentWeekEnd(?CarbonInterface $now = null): CarbonImmutable
    {
        return $this->currentWeekStart($now)->addDays(6);
    }

    public function currentFor(User $user, ?CarbonInterface $now = null): ?WeeklyCheckIn
    {
        return WeeklyCheckIn::query()
            ->where('user_id', $user->getKey())
            ->whereDate('week_start_date', $this->currentWeekStart($now)->toDateString())
            ->first();
    }

    public function isEditable(WeeklyCheckIn $checkIn, ?CarbonInterface $now = null): bool
    {
        return $checkIn->week_start_date->isSameDay($this->currentWeekStart($now));
    }

    public function weekLabel(CarbonInterface|string $weekStartDate): string
    {
        $start = $weekStartDate instanceof CarbonInterface
            ? CarbonImmutable::instance($weekStartDate)
            : CarbonImmutable::parse($weekStartDate);
        $end = $start->addDays(6);

        if ($start->month === $end->month) {
            return sprintf('%s %d - %d', $start->format('M'), $start->day, $end->day);
        }

        return sprintf('%s %d - %s %d', $start->format('M'), $start->day, $end->format('M'), $end->day);
    }

    public function historyFor(User $user, int $perPage = 10): LengthAwarePaginator
    {
        return WeeklyCheckIn::query()
            ->where('user_id', $user->getKey())
            ->latest('week_start_date')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function trendCardsFor(User $user): array
    {
        $entries = WeeklyCheckIn::query()
            ->where('user_id', $user->getKey())
            ->latest('week_start_date')
            ->limit(8)
            ->get([
                'week_start_date',
                'emotional_availability',
                'communication_effort',
                'stress_level',
            ])
            ->sortBy('week_start_date')
            ->values();

        return [
            $this->trendCard('Emotional availability', $entries, 'emotional_availability'),
            $this->trendCard('Communication effort', $entries, 'communication_effort'),
            $this->trendCard('Stress level', $entries, 'stress_level'),
        ];
    }

    private function trendCard(string $label, Collection $entries, string $key): array
    {
        $series = $entries->pluck($key)->map(fn (int $value) => $value)->values()->all();

        return [
            'label' => $label,
            'trend' => $this->trendLabel($series),
            'series' => $series,
        ];
    }

    private function trendLabel(array $series): string
    {
        if (count($series) < 2) {
            return 'Stable';
        }

        $deltas = collect($series)
            ->slice(1)
            ->values()
            ->map(fn (int $value, int $index) => $value - $series[$index]);

        if ($deltas->every(fn (int $delta) => $delta === 0)) {
            return 'Stable';
        }

        if ($deltas->every(fn (int $delta) => $delta >= 0) && end($series) > $series[0]) {
            return 'Rising';
        }

        return 'Mixed';
    }
}
