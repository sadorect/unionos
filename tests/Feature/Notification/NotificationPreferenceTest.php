<?php

use App\Domains\Couple\Enums\CoupleStatus;
use App\Domains\Couple\Models\Couple;
use App\Domains\Couple\Models\CoupleMembership;
use App\Domains\Notification\Models\NotificationPreference;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('users can view and update notification preferences', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('settings.notifications.show'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Settings/Notifications')
            ->where('preferences.weeklyEmailEnabled', true)
            ->where('preferences.quietModeEnabled', false)
        );

    $this->actingAs($user)
        ->put(route('settings.notifications.update'), [
            'weekly_email_enabled' => true,
            'weekly_day_of_week' => 2,
            'weekly_hour' => 19,
            'monthly_email_enabled' => false,
            'monthly_day_of_month' => 5,
            'monthly_hour' => 18,
            'conversation_email_enabled' => true,
            'conversation_in_app_enabled' => false,
            'conversation_hour' => 20,
            'timezone' => 'Europe/London',
            'quiet_mode_enabled' => false,
        ])
        ->assertRedirect(route('settings.notifications.show', absolute: false));

    $preference = NotificationPreference::query()->where('user_id', $user->id)->firstOrFail();

    expect($preference->weekly_day_of_week)->toBe(2);
    expect($preference->monthly_email_enabled)->toBeFalse();
    expect($preference->conversation_email_enabled)->toBeTrue();
    expect($preference->timezone)->toBe('Europe/London');
});

test('quiet mode redirects to the paused reminders state', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->put(route('settings.notifications.update'), [
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
        ])
        ->assertRedirect(route('settings.notifications.paused', absolute: false));

    $this->actingAs($user)
        ->get(route('settings.notifications.paused'))
        ->assertInertia(fn (Assert $page) => $page->component('State/NotificationsPaused'));
});

test('active home renders a safe next step nudge when reminders are not paused', function () {
    $user = User::factory()->create();
    activeNotificationCoupleFor($user, User::factory()->create());

    $this->actingAs($user)
        ->get(route('couple.show'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Couple/ActiveHome')
            ->where('nextStep.title', "Ready for this week's check-in?")
            ->where('notificationsPaused', false)
        );
});

function activeNotificationCoupleFor(User ...$users): Couple
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
