<?php

namespace App\Domains\Dashboard\Services;

use App\Domains\CheckIn\Models\MonthlyCoupleCheckIn;
use App\Domains\CheckIn\Models\WeeklyCheckIn;
use App\Domains\CheckIn\Services\MonthlyInsightService;
use App\Domains\CheckIn\Services\WeeklyCheckInService;
use App\Domains\Conversation\Models\ConversationSession;
use App\Domains\Conversation\Services\ConversationPackService;
use App\Domains\Couple\Enums\CoupleStatus;
use App\Domains\Couple\Services\CoupleStateService;
use App\Domains\Notification\Services\NextStepNudgeService;
use App\Domains\Notification\Services\NotificationPreferenceService;
use App\Models\User;
use Illuminate\Support\Collection;

class DashboardService
{
    public function __construct(
        private readonly WeeklyCheckInService $weeklyCheckIns,
        private readonly MonthlyInsightService $monthlyInsights,
        private readonly CoupleStateService $coupleState,
        private readonly ConversationPackService $packs,
        private readonly NextStepNudgeService $nudges,
        private readonly NotificationPreferenceService $preferences,
    ) {
    }

    public function personal(User $user): array
    {
        $weeklyCount = WeeklyCheckIn::query()
            ->where('user_id', $user->id)
            ->count();

        $recentWeeks = WeeklyCheckIn::query()
            ->where('user_id', $user->id)
            ->latest('week_start_date')
            ->limit(6)
            ->get();

        $conversationHistory = ConversationSession::query()
            ->whereHas('couple.memberships', fn ($query) => $query->where('user_id', $user->id))
            ->latest('id')
            ->limit(6)
            ->get();

        $historyPreview = WeeklyCheckIn::query()
            ->where('user_id', $user->id)
            ->latest('week_start_date')
            ->limit(3)
            ->get()
            ->map(fn (WeeklyCheckIn $checkIn) => [
                'id' => $checkIn->id,
                'weekLabel' => 'Week of '.$this->weeklyCheckIns->weekLabel($checkIn->week_start_date),
                'savedAt' => optional($checkIn->updated_at)->toIso8601String(),
            ])
            ->all();

        return [
            'overviewCards' => [
                [
                    'label' => 'Weekly check-ins completed',
                    'value' => "{$weeklyCount} completed",
                ],
                [
                    'label' => 'Recent reflection consistency',
                    'value' => $this->consistencyLabel($recentWeeks->count()),
                ],
                [
                    'label' => 'Conversation participation',
                    'value' => $conversationHistory->where('status', 'completed')->count().' conversations completed',
                ],
            ],
            'trendCards' => $this->weeklyCheckIns->trendCardsFor($user),
            'historyPreview' => $historyPreview,
            'conversationActivity' => $conversationHistory->map(fn (ConversationSession $session) => [
                'id' => $session->id,
                'packTitle' => $this->packs->titleFor($session->pack_type, $session->pack_version),
                'status' => $session->status->value,
                'completedAt' => optional($session->completed_at)->toIso8601String(),
            ])->all(),
            'isEmpty' => $weeklyCount === 0 && $conversationHistory->isEmpty(),
            'nextStep' => $this->nudges->forUser($user),
            'notificationsPaused' => $this->preferences->forUser($user)->quiet_mode_enabled,
        ];
    }

    public function couple(User $user): array
    {
        $couple = $this->coupleState->current($user);

        if ($couple === null) {
            return [
                'state' => 'none',
                'overviewCards' => [],
                'alignmentRows' => [],
                'conversationActivity' => [],
                'nextStep' => null,
                'isEmpty' => true,
            ];
        }

        $alignmentRows = MonthlyCoupleCheckIn::query()
            ->where('couple_id', $couple->id)
            ->select('month')
            ->distinct()
            ->orderByDesc('month')
            ->get()
            ->map(fn (MonthlyCoupleCheckIn $entry) => $entry->month)
            ->values()
            ->map(function (string $month) use ($couple) {
                $submissions = $this->monthlyInsights->submissionsForCouple($couple, $month);

                if ($submissions->count() < 2) {
                    return [
                        'monthLabel' => \Carbon\CarbonImmutable::createFromFormat('Y-m', $month)->format('F Y'),
                        'status' => 'Mixed',
                    ];
                }

                $cards = $this->monthlyInsights->insightCards($submissions->first()->user, $month);
                $needsConversation = collect($cards)->where('state', 'needs conversation')->count();
                $status = match (true) {
                    $needsConversation === 0 => 'Stable',
                    $needsConversation === count($cards) => 'Needs attention',
                    default => 'Mixed',
                };

                return [
                    'monthLabel' => \Carbon\CarbonImmutable::createFromFormat('Y-m', $month)->format('F Y'),
                    'status' => $status,
                ];
            })
            ->take(6)
            ->all();

        $conversationActivity = ConversationSession::query()
            ->where('couple_id', $couple->id)
            ->latest('id')
            ->limit(6)
            ->get()
            ->map(fn (ConversationSession $session) => [
                'id' => $session->id,
                'packTitle' => $this->packs->titleFor($session->pack_type, $session->pack_version),
                'status' => $session->status->value,
                'completedAt' => optional($session->completed_at)->toIso8601String(),
            ])
            ->all();

        $monthlyCompleteCount = collect($alignmentRows)
            ->filter(fn (array $row) => in_array($row['status'], ['Stable', 'Mixed', 'Needs attention'], true))
            ->count();

        $nextStep = $this->nudges->forUser($user);

        return [
            'state' => $couple->status->value,
            'overviewCards' => [
                [
                    'label' => 'Monthly check-ins completed',
                    'value' => "{$monthlyCompleteCount} monthly check-ins completed",
                ],
                [
                    'label' => 'Guided conversations completed',
                    'value' => collect($conversationActivity)->where('status', 'completed')->count().' guided conversations completed',
                ],
                [
                    'label' => 'Recent alignment status',
                    'value' => $alignmentRows[0]['status'] ?? 'Not enough history yet',
                ],
            ],
            'alignmentRows' => $alignmentRows,
            'conversationActivity' => $conversationActivity,
            'nextStep' => $nextStep,
            'isEmpty' => empty($alignmentRows) && empty($conversationActivity),
            'notificationsPaused' => $this->preferences->forUser($user)->quiet_mode_enabled,
        ];
    }

    private function consistencyLabel(int $recentCount): string
    {
        return match (true) {
            $recentCount >= 5 => 'Mostly consistent',
            $recentCount >= 3 => 'Steady lately',
            $recentCount >= 1 => 'Building a rhythm',
            default => 'Not enough history yet',
        };
    }
}
