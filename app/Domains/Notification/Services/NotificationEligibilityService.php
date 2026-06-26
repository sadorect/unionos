<?php

namespace App\Domains\Notification\Services;

use App\Domains\CheckIn\Models\WeeklyCheckIn;
use App\Domains\CheckIn\Services\MonthlyInsightService;
use App\Domains\CheckIn\Services\WeeklyCheckInService;
use App\Domains\Conversation\Models\ConversationSession;
use App\Domains\Couple\Enums\CoupleStatus;
use App\Domains\Couple\Services\CoupleStateService;
use App\Domains\Notification\Models\NotificationDispatchLog;
use App\Models\User;
use Carbon\CarbonImmutable;

class NotificationEligibilityService
{
    public function __construct(
        private readonly NotificationPreferenceService $preferences,
        private readonly CoupleStateService $coupleState,
        private readonly WeeklyCheckInService $weeklyCheckIns,
        private readonly MonthlyInsightService $monthlyInsights,
    ) {
    }

    public function nextDueNotification(User $user, ?CarbonImmutable $now = null): ?string
    {
        $moment = $now ?? CarbonImmutable::now();

        foreach (['weekly', 'monthly', 'conversation'] as $kind) {
            if ($this->isDue($user, $kind, $moment)) {
                return $kind;
            }
        }

        return null;
    }

    public function suppressionReason(User $user, string $kind, ?CarbonImmutable $now = null): ?string
    {
        $moment = $now ?? CarbonImmutable::now();
        $preference = $this->preferences->forUser($user);

        if ($preference->quiet_mode_enabled) {
            return 'quiet_mode';
        }

        if ($this->coupleState->stateFor($user) !== CoupleStatus::Active->value) {
            return 'inactive_couple';
        }

        if ($this->hasActiveConversation($user)) {
            return 'active_conversation';
        }

        if ($this->sentRecently($user, $moment)) {
            return 'cooldown';
        }

        return match ($kind) {
            'weekly' => $this->weeklySuppressionReason($user, $moment),
            'monthly' => $this->monthlySuppressionReason($user, $moment),
            'conversation' => $this->conversationSuppressionReason($user, $moment),
            default => 'unsupported',
        };
    }

    public function isDue(User $user, string $kind, ?CarbonImmutable $now = null): bool
    {
        $moment = $now ?? CarbonImmutable::now();
        $preference = $this->preferences->forUser($user);

        if (! $this->channelEnabled($preference, $kind)) {
            return false;
        }

        if (! $this->matchesPreferredWindow($preference, $kind, $moment)) {
            return false;
        }

        return $this->suppressionReason($user, $kind, $moment) === null;
    }

    private function channelEnabled($preference, string $kind): bool
    {
        return match ($kind) {
            'weekly' => $preference->weekly_email_enabled,
            'monthly' => $preference->monthly_email_enabled,
            'conversation' => $preference->conversation_email_enabled,
            default => false,
        };
    }

    private function matchesPreferredWindow($preference, string $kind, CarbonImmutable $now): bool
    {
        $local = $now->setTimezone($preference->timezone);
        $startHour = (int) config('unionos.notifications.allowed_start_hour', 8);
        $endHour = (int) config('unionos.notifications.allowed_end_hour', 21);

        if ($local->hour < $startHour || $local->hour > $endHour) {
            return false;
        }

        return match ($kind) {
            'weekly' => $local->dayOfWeekIso === (int) $preference->weekly_day_of_week
                && $local->hour === (int) $preference->weekly_hour,
            'monthly' => $local->day === (int) $preference->monthly_day_of_month
                && $local->hour === (int) $preference->monthly_hour,
            'conversation' => $local->hour === (int) $preference->conversation_hour,
            default => false,
        };
    }

    private function weeklySuppressionReason(User $user, CarbonImmutable $now): ?string
    {
        return $this->weeklyCheckIns->currentFor($user, $now) instanceof WeeklyCheckIn
            ? 'recent_completion'
            : null;
    }

    private function monthlySuppressionReason(User $user, CarbonImmutable $now): ?string
    {
        return $this->monthlyInsights->submissionFor($user, $this->monthlyInsights->currentMonth($now)) !== null
            ? 'recent_completion'
            : null;
    }

    private function conversationSuppressionReason(User $user, CarbonImmutable $now): ?string
    {
        if (! $this->monthlyInsights->isInsightReady($user, $this->monthlyInsights->currentMonth($now))) {
            return 'no_actionable_insight';
        }

        $couple = $this->coupleState->current($user);

        if ($couple === null) {
            return 'inactive_couple';
        }

        $recentConversation = ConversationSession::query()
            ->where('couple_id', $couple->id)
            ->where('status', 'completed')
            ->where('completed_at', '>=', $now->subDays((int) config('unionos.notifications.conversation_cooldown_days', 7)))
            ->exists();

        return $recentConversation ? 'recent_completion' : null;
    }

    private function sentRecently(User $user, CarbonImmutable $now): bool
    {
        return NotificationDispatchLog::query()
            ->where('user_id', $user->id)
            ->where('status', 'sent')
            ->where('delivered_at', '>=', $now->subHours((int) config('unionos.notifications.cooldown_hours', 24)))
            ->exists();
    }

    private function hasActiveConversation(User $user): bool
    {
        $couple = $this->coupleState->current($user);

        if ($couple === null) {
            return false;
        }

        return ConversationSession::query()
            ->where('couple_id', $couple->id)
            ->whereIn('status', ['active', 'paused'])
            ->exists();
    }
}
