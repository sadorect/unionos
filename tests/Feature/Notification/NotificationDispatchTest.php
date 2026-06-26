<?php

use App\Domains\CheckIn\Models\MonthlyCoupleCheckIn;
use App\Domains\Conversation\Models\ConversationSession;
use App\Domains\Couple\Enums\CoupleStatus;
use App\Domains\Couple\Models\Couple;
use App\Domains\Couple\Models\CoupleMembership;
use App\Domains\Notification\Models\NotificationDispatchLog;
use App\Domains\Notification\Services\NotificationPreferenceService;
use App\Jobs\SendUnionNotificationJob;
use App\Models\User;
use App\Notifications\ConversationSuggestionNotification;
use App\Notifications\WeeklyCheckInReminderNotification;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {
    Carbon::setTestNow(CarbonImmutable::parse('2026-03-02 18:00:00'));
});

afterEach(function () {
    Carbon::setTestNow();
});

test('dispatch notifications command queues a weekly reminder job for an eligible user', function () {
    Bus::fake();

    $user = User::factory()->create();
    activeDispatchCoupleFor($user, User::factory()->create());
    app(NotificationPreferenceService::class)->update($user, [
        'weekly_email_enabled' => true,
        'weekly_day_of_week' => 1,
        'weekly_hour' => 18,
        'monthly_email_enabled' => true,
        'monthly_day_of_month' => 1,
        'monthly_hour' => 18,
        'conversation_email_enabled' => false,
        'conversation_in_app_enabled' => true,
        'conversation_hour' => 18,
        'timezone' => 'UTC',
        'quiet_mode_enabled' => false,
    ]);

    Artisan::call('unionos:dispatch-notifications');

    Bus::assertDispatched(SendUnionNotificationJob::class, fn (SendUnionNotificationJob $job) => $job->userId === $user->id && $job->kind === 'weekly');
});

test('dispatch notifications command suppresses users in quiet mode', function () {
    Bus::fake();

    $user = User::factory()->create();
    activeDispatchCoupleFor($user, User::factory()->create());
    app(NotificationPreferenceService::class)->update($user, [
        'weekly_email_enabled' => true,
        'weekly_day_of_week' => 1,
        'weekly_hour' => 18,
        'monthly_email_enabled' => true,
        'monthly_day_of_month' => 1,
        'monthly_hour' => 18,
        'conversation_email_enabled' => false,
        'conversation_in_app_enabled' => true,
        'conversation_hour' => 18,
        'timezone' => 'UTC',
        'quiet_mode_enabled' => true,
    ]);

    Artisan::call('unionos:dispatch-notifications');

    Bus::assertNotDispatched(SendUnionNotificationJob::class, fn (SendUnionNotificationJob $job) => $job->userId === $user->id);
});

test('queued weekly reminder sends a notification and records a dispatch log', function () {
    Notification::fake();

    $user = User::factory()->create();
    activeDispatchCoupleFor($user, User::factory()->create());
    app(NotificationPreferenceService::class)->update($user, [
        'weekly_email_enabled' => true,
        'weekly_day_of_week' => 1,
        'weekly_hour' => 18,
        'monthly_email_enabled' => true,
        'monthly_day_of_month' => 1,
        'monthly_hour' => 18,
        'conversation_email_enabled' => false,
        'conversation_in_app_enabled' => true,
        'conversation_hour' => 18,
        'timezone' => 'UTC',
        'quiet_mode_enabled' => false,
    ]);

    (new SendUnionNotificationJob($user->id, 'weekly'))->handle(app(\App\Domains\Notification\Services\NotificationEligibilityService::class));

    Notification::assertSentTo($user, WeeklyCheckInReminderNotification::class);
    expect(NotificationDispatchLog::query()->where('user_id', $user->id)->where('kind', 'weekly')->where('status', 'sent')->exists())->toBeTrue();
});

test('conversation suggestions are suppressed while an active conversation is in progress', function () {
    Notification::fake();

    $user = User::factory()->create();
    $partner = User::factory()->create();
    $couple = activeDispatchCoupleFor($user, $partner);

    app(NotificationPreferenceService::class)->update($user, [
        'weekly_email_enabled' => true,
        'weekly_day_of_week' => 1,
        'weekly_hour' => 18,
        'monthly_email_enabled' => true,
        'monthly_day_of_month' => 1,
        'monthly_hour' => 18,
        'conversation_email_enabled' => true,
        'conversation_in_app_enabled' => true,
        'conversation_hour' => 18,
        'timezone' => 'UTC',
        'quiet_mode_enabled' => false,
    ]);

    foreach ([$user, $partner] as $actor) {
        MonthlyCoupleCheckIn::query()->create([
            'user_id' => $actor->id,
            'couple_id' => $couple->id,
            'month' => '2026-03',
            'felt_heard' => 4,
            'felt_appreciated' => 4,
            'conflict_safety' => 4,
            'emotional_closeness' => 4,
            'submitted_at' => now(),
        ]);
    }

    ConversationSession::query()->create([
        'couple_id' => $couple->id,
        'started_by_user_id' => $user->id,
        'current_turn_user_id' => $partner->id,
        'pack_type' => 'weekly-reset',
        'pack_version' => 1,
        'status' => 'active',
        'ground_rules_confirmed_at' => now()->subMinutes(15),
        'started_at' => now()->subMinutes(15),
        'last_activity_at' => now()->subMinutes(5),
    ]);

    (new SendUnionNotificationJob($user->id, 'conversation'))->handle(app(\App\Domains\Notification\Services\NotificationEligibilityService::class));

    Notification::assertNothingSent();
    expect(NotificationDispatchLog::query()->where('user_id', $user->id)->where('kind', 'conversation')->where('status', 'suppressed')->where('reason', 'active_conversation')->exists())->toBeTrue();
});

function activeDispatchCoupleFor(User ...$users): Couple
{
    $couple = Couple::factory()->create([
        'status' => CoupleStatus::Active,
    ]);

    foreach ($users as $index => $user) {
        CoupleMembership::query()->create([
            'couple_id' => $couple->id,
            'user_id' => $user->id,
            'slot' => $index + 1,
            'joined_at' => now(),
            'is_active' => true,
        ]);
    }

    return $couple;
}
