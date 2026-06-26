<?php

namespace App\Domains\CheckIn\Services;

use App\Domains\CheckIn\Models\MonthlyCoupleCheckIn;
use App\Domains\Couple\Enums\CoupleStatus;
use App\Domains\Couple\Models\Couple;
use App\Domains\Couple\Services\CoupleStateService;
use App\Models\User;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;

class MonthlyInsightService
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

    public function currentMonth(?CarbonInterface $now = null): string
    {
        return CarbonImmutable::instance($now ?? now())->format('Y-m');
    }

    public function currentMonthLabel(?CarbonInterface $now = null): string
    {
        return CarbonImmutable::instance($now ?? now())->format('F Y');
    }

    public function submissionFor(User $user, ?string $month = null): ?MonthlyCoupleCheckIn
    {
        return MonthlyCoupleCheckIn::query()
            ->where('user_id', $user->getKey())
            ->where('month', $month ?? $this->currentMonth())
            ->whereNull('archived_at')
            ->first();
    }

    public function submissionsForCouple(Couple $couple, ?string $month = null): Collection
    {
        return MonthlyCoupleCheckIn::query()
            ->where('couple_id', $couple->getKey())
            ->where('month', $month ?? $this->currentMonth())
            ->whereNull('archived_at')
            ->get();
    }

    public function isInsightReady(User $user, ?string $month = null): bool
    {
        $couple = $this->activeCoupleFor($user);

        if ($couple === null) {
            return false;
        }

        return $this->submissionsForCouple($couple, $month)->count() >= 2;
    }

    public function insightCards(User $user, ?string $month = null): array
    {
        $couple = $this->activeCoupleFor($user);
        $submissions = $couple ? $this->submissionsForCouple($couple, $month)->values() : collect();

        if ($submissions->count() < 2) {
            return [];
        }

        $first = $submissions->get(0);
        $second = $submissions->get(1);

        return [
            $this->classify('Feeling heard', 'felt_heard', $first, $second),
            $this->classify('Feeling appreciated', 'felt_appreciated', $first, $second),
            $this->classify('Conflict safety', 'conflict_safety', $first, $second),
            $this->classify('Emotional closeness', 'emotional_closeness', $first, $second),
        ];
    }

    private function classify(string $label, string $attribute, MonthlyCoupleCheckIn $first, MonthlyCoupleCheckIn $second): array
    {
        $difference = abs($first->{$attribute} - $second->{$attribute});
        $state = $difference <= 1 ? 'aligned' : 'needs conversation';

        return [
            'label' => $label,
            'state' => $state,
            'description' => $state === 'aligned'
                ? 'You feel similarly in this area.'
                : 'You may feel differently in this area.',
        ];
    }
}
