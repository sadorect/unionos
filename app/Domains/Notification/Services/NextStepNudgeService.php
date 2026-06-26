<?php

namespace App\Domains\Notification\Services;

use App\Domains\CheckIn\Services\MonthlyInsightService;
use App\Domains\CheckIn\Services\WeeklyCheckInService;
use App\Domains\Conversation\Models\ConversationSession;
use App\Domains\Couple\Enums\CoupleStatus;
use App\Domains\Couple\Services\CoupleStateService;
use App\Models\User;

class NextStepNudgeService
{
    public function __construct(
        private readonly NotificationPreferenceService $preferences,
        private readonly CoupleStateService $coupleState,
        private readonly WeeklyCheckInService $weeklyCheckIns,
        private readonly MonthlyInsightService $monthlyInsights,
    ) {
    }

    public function forUser(User $user): ?array
    {
        $preference = $this->preferences->forUser($user);

        if ($preference->quiet_mode_enabled) {
            return null;
        }

        if ($this->coupleState->stateFor($user) !== CoupleStatus::Active->value) {
            return null;
        }

        if ($this->weeklyCheckIns->currentFor($user) === null) {
            return [
                'title' => "Ready for this week's check-in?",
                'description' => 'Your weekly check-in is available whenever it feels helpful.',
                'actionLabel' => 'Open weekly check-in',
                'actionUrl' => route('checkins.weekly.show', absolute: false),
            ];
        }

        if ($this->monthlyInsights->submissionFor($user) === null) {
            return [
                'title' => 'Your monthly check-in is available.',
                'description' => 'Open the shared monthly reflection when it feels like a good moment.',
                'actionLabel' => 'Open monthly check-in',
                'actionUrl' => route('checkins.monthly.show', absolute: false),
            ];
        }

        if (! $preference->conversation_in_app_enabled || ! $this->monthlyInsights->isInsightReady($user)) {
            return null;
        }

        $couple = $this->coupleState->current($user);

        if ($couple === null) {
            return null;
        }

        $hasActiveConversation = ConversationSession::query()
            ->where('couple_id', $couple->id)
            ->whereIn('status', ['active', 'paused'])
            ->exists();

        if ($hasActiveConversation) {
            return null;
        }

        return [
            'title' => "A guided conversation is available if you'd like one.",
            'description' => 'Choose a structured conversation if you want a calmer space to talk together.',
            'actionLabel' => 'Open conversation packs',
            'actionUrl' => route('conversations.index', absolute: false),
        ];
    }
}
