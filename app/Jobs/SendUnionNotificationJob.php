<?php

namespace App\Jobs;

use App\Events\FeatureActivityOccurred;
use App\Domains\Notification\Models\NotificationDispatchLog;
use App\Domains\Notification\Services\NotificationEligibilityService;
use App\Models\User;
use App\Notifications\ConversationSuggestionNotification;
use App\Notifications\MonthlyCheckInOpenNotification;
use App\Notifications\WeeklyCheckInReminderNotification;
use Carbon\CarbonImmutable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use Throwable;

class SendUnionNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public readonly int $userId,
        public readonly string $kind,
    ) {
    }

    public function handle(NotificationEligibilityService $eligibility): void
    {
        $user = User::query()->find($this->userId);

        if ($user === null) {
            return;
        }

        $moment = CarbonImmutable::now();
        $reason = $eligibility->suppressionReason($user, $this->kind, $moment);

        if ($reason !== null) {
            NotificationDispatchLog::query()->create([
                'user_id' => $user->id,
                'kind' => $this->kind,
                'channel' => 'mail',
                'status' => 'suppressed',
                'reason' => $reason,
            ]);

            event(new FeatureActivityOccurred('notification', 'suppressed', [
                'status' => 'suppressed',
                'kind' => $this->kind,
                'channel' => 'mail',
            ]));

            return;
        }

        $notification = match ($this->kind) {
            'weekly' => new WeeklyCheckInReminderNotification(),
            'monthly' => new MonthlyCheckInOpenNotification(),
            'conversation' => new ConversationSuggestionNotification(),
            default => null,
        };

        if ($notification === null) {
            return;
        }

        try {
            $user->notify($notification);
        } catch (Throwable $e) {
            NotificationDispatchLog::query()->create([
                'user_id' => $user->id,
                'kind' => $this->kind,
                'channel' => 'mail',
                'status' => 'failed',
                'reason' => Str::limit($e->getMessage(), 250),
            ]);

            event(new FeatureActivityOccurred('notification', 'failed', [
                'status' => 'failed',
                'kind' => $this->kind,
                'channel' => 'mail',
            ]));

            throw $e;
        }

        NotificationDispatchLog::query()->create([
            'user_id' => $user->id,
            'kind' => $this->kind,
            'channel' => 'mail',
            'status' => 'sent',
            'delivered_at' => $moment,
        ]);

        event(new FeatureActivityOccurred('notification', 'sent', [
            'status' => 'sent',
            'kind' => $this->kind,
            'channel' => 'mail',
        ]));
    }
}
