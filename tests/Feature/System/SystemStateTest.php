<?php

use App\Domains\CheckIn\Models\MonthlyCoupleCheckIn;
use App\Domains\Conversation\Models\ConversationSession;
use App\Domains\Couple\Enums\CoupleStatus;
use App\Domains\Couple\Models\Couple;
use App\Domains\Couple\Models\CoupleMembership;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Artisan;

beforeEach(function () {
    Carbon::setTestNow(CarbonImmutable::parse('2026-04-05 12:00:00'));
});

afterEach(function () {
    Carbon::setTestNow();
});

test('deactivating a user soft deletes the account and dissolves the active couple', function () {
    $user = User::factory()->create();
    $partner = User::factory()->create();
    $couple = activeSystemCoupleFor($user, $partner);

    Artisan::call('unionos:deactivate-user', [
        'user' => (string) $user->id,
    ]);

    expect(User::withTrashed()->find($user->id)?->trashed())->toBeTrue();
    expect($couple->fresh()->status->value)->toBe(CoupleStatus::Dissolved->value);
});

test('advance system state archives incomplete monthly periods from past months', function () {
    $user = User::factory()->create();
    $partner = User::factory()->create();
    $couple = activeSystemCoupleFor($user, $partner);

    $submission = MonthlyCoupleCheckIn::query()->create([
        'user_id' => $user->id,
        'couple_id' => $couple->id,
        'month' => '2026-03',
        'felt_heard' => 4,
        'felt_appreciated' => 4,
        'conflict_safety' => 4,
        'emotional_closeness' => 4,
        'submitted_at' => now()->subMonth(),
    ]);

    Artisan::call('unionos:advance-system-state');

    expect($submission->fresh()->archived_at)->not->toBeNull();
});

test('advance system state marks stale conversation sessions as incomplete', function () {
    $user = User::factory()->create();
    $partner = User::factory()->create();
    $couple = activeSystemCoupleFor($user, $partner);

    $session = ConversationSession::query()->create([
        'couple_id' => $couple->id,
        'started_by_user_id' => $user->id,
        'current_turn_user_id' => $partner->id,
        'pack_type' => 'weekly-reset',
        'pack_version' => 1,
        'status' => 'active',
        'ground_rules_confirmed_at' => now()->subDays(2),
        'started_at' => now()->subDays(2),
        'last_activity_at' => now()->subDays(2),
    ]);

    Artisan::call('unionos:advance-system-state');

    expect($session->fresh()->status->value)->toBe('abandoned');
});

test('dissolved couple members are redirected to the dissolved state for active features', function () {
    $user = User::factory()->create();
    $partner = User::factory()->create();
    $couple = activeSystemCoupleFor($user, $partner);
    $couple->update(['status' => CoupleStatus::Dissolved]);

    $response = $this->actingAs($user)->get(route('checkins.weekly.show'));

    $response->assertRedirect(route('state.couple-dissolved', absolute: false));
});

test('completed conversation routes redirect to the completed state page', function () {
    $user = User::factory()->create();
    $partner = User::factory()->create();
    $couple = activeSystemCoupleFor($user, $partner);

    $session = ConversationSession::query()->create([
        'couple_id' => $couple->id,
        'started_by_user_id' => $user->id,
        'current_turn_user_id' => null,
        'pack_type' => 'weekly-reset',
        'pack_version' => 1,
        'status' => 'completed',
        'ground_rules_confirmed_at' => now()->subHour(),
        'started_at' => now()->subHour(),
        'completed_at' => now()->subMinutes(10),
        'last_activity_at' => now()->subMinutes(10),
    ]);

    $this->actingAs($user)
        ->get(route('conversations.show', $session))
        ->assertRedirect(route('state.conversation-completed', [
            'packTitle' => 'Weekly Reset',
        ], absolute: false));
});

test('paused conversation routes redirect to the paused state page', function () {
    $user = User::factory()->create();
    $partner = User::factory()->create();
    $couple = activeSystemCoupleFor($user, $partner);

    $session = ConversationSession::query()->create([
        'couple_id' => $couple->id,
        'started_by_user_id' => $user->id,
        'current_turn_user_id' => $partner->id,
        'pack_type' => 'weekly-reset',
        'pack_version' => 1,
        'status' => 'paused',
        'ground_rules_confirmed_at' => now()->subHour(),
        'started_at' => now()->subHour(),
        'paused_at' => now()->subMinutes(5),
        'last_activity_at' => now()->subMinutes(5),
    ]);

    $this->actingAs($user)
        ->get(route('conversations.show', $session))
        ->assertRedirect(route('state.session-paused', [
            'id' => $session->id,
            'packTitle' => 'Weekly Reset',
        ], absolute: false));
});

test('abandoned conversation routes redirect to the incomplete state page', function () {
    $user = User::factory()->create();
    $partner = User::factory()->create();
    $couple = activeSystemCoupleFor($user, $partner);

    $session = ConversationSession::query()->create([
        'couple_id' => $couple->id,
        'started_by_user_id' => $user->id,
        'current_turn_user_id' => $partner->id,
        'pack_type' => 'weekly-reset',
        'pack_version' => 1,
        'status' => 'abandoned',
        'ground_rules_confirmed_at' => now()->subDays(2),
        'started_at' => now()->subDays(2),
        'last_activity_at' => now()->subDays(2),
    ]);

    $this->actingAs($user)
        ->get(route('conversations.show', $session))
        ->assertRedirect(route('state.conversation-incomplete', [
            'packTitle' => 'Weekly Reset',
        ], absolute: false));
});

function activeSystemCoupleFor(User ...$users): Couple
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
