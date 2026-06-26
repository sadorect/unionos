<?php

use App\Domains\CheckIn\Models\WeeklyCheckIn;
use App\Domains\Couple\Enums\CoupleStatus;
use App\Domains\Couple\Models\Couple;
use App\Domains\Couple\Models\CoupleMembership;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    Carbon::setTestNow(CarbonImmutable::parse('2026-03-04 12:00:00'));
});

afterEach(function () {
    Carbon::setTestNow();
});

test('active couple members can submit a weekly check-in for the current iso week', function () {
    $user = User::factory()->create();
    $couple = activeCoupleFor($user, User::factory()->create());

    $response = $this->actingAs($user)->post(route('checkins.weekly.store'), weeklyCheckInPayload([
        'appreciation_text' => 'I appreciated the quiet support this week.',
    ]));

    $response->assertRedirect(route('checkins.weekly.submitted', absolute: false));
    $checkIn = WeeklyCheckIn::query()->firstOrFail();

    expect($checkIn->user_id)->toBe($user->id);
    expect($checkIn->couple_id)->toBe($couple->id);
    expect($checkIn->week_start_date->toDateString())->toBe('2026-03-02');
    expect($checkIn->emotional_availability)->toBe(4);
    expect($checkIn->communication_effort)->toBe(4);
    expect($checkIn->stress_level)->toBe(2);
});

test('current week check-ins can be updated within the same week without creating duplicates', function () {
    $user = User::factory()->create();
    activeCoupleFor($user, User::factory()->create());

    $this->actingAs($user)->post(route('checkins.weekly.store'), weeklyCheckInPayload([
        'emotional_availability' => 2,
        'appreciation_text' => 'First pass.',
    ]));

    $response = $this->actingAs($user)->post(route('checkins.weekly.store'), weeklyCheckInPayload([
        'emotional_availability' => 5,
        'communication_effort' => 3,
        'stress_level' => 1,
        'appreciation_text' => 'Updated reflection.',
    ]));

    $response->assertRedirect(route('checkins.weekly.submitted', absolute: false));
    expect(WeeklyCheckIn::query()->count())->toBe(1);

    $checkIn = WeeklyCheckIn::query()->firstOrFail();

    expect($checkIn->week_start_date->toDateString())->toBe('2026-03-02');
    expect($checkIn->emotional_availability)->toBe(5);
    expect($checkIn->communication_effort)->toBe(3);
    expect($checkIn->stress_level)->toBe(1);
    expect($checkIn->appreciation_text)->toBe('Updated reflection.');
});

test('weekly form loads in edit mode when a current week check-in already exists', function () {
    $user = User::factory()->create();
    $couple = activeCoupleFor($user, User::factory()->create());

    WeeklyCheckIn::query()->create([
        'user_id' => $user->id,
        'couple_id' => $couple->id,
        'week_start_date' => '2026-03-02',
        'emotional_availability' => 3,
        'communication_effort' => 4,
        'stress_level' => 2,
        'appreciation_text' => 'Still private.',
    ]);

    $this->actingAs($user)
        ->get(route('checkins.weekly.show'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('CheckIn/WeeklyForm')
            ->where('isEditing', true)
            ->where('checkIn.emotionalAvailability', 3)
            ->where('checkIn.appreciationText', 'Still private.')
        );
});

test('backfill input is ignored and the current week is always stored', function () {
    $user = User::factory()->create();
    activeCoupleFor($user, User::factory()->create());

    $this->actingAs($user)->post(route('checkins.weekly.store'), array_merge(
        weeklyCheckInPayload(),
        ['week_start_date' => '2026-02-23'],
    ));

    $checkIn = WeeklyCheckIn::query()->firstOrFail();

    expect($checkIn->user_id)->toBe($user->id);
    expect($checkIn->week_start_date->toDateString())->toBe('2026-03-02');
});

test('pending couple members are redirected away from weekly check-ins', function () {
    $user = User::factory()->create();
    pendingCoupleFor($user);

    $response = $this->actingAs($user)->get(route('checkins.weekly.show'));

    $response->assertRedirect(route('state.couple-pending', absolute: false));
});

test('weekly history only includes the signed-in users own raw entries', function () {
    $user = User::factory()->create();
    $partner = User::factory()->create();
    $couple = activeCoupleFor($user, $partner);

    WeeklyCheckIn::query()->create([
        'user_id' => $user->id,
        'couple_id' => $couple->id,
        'week_start_date' => '2026-03-02',
        'emotional_availability' => 4,
        'communication_effort' => 4,
        'stress_level' => 2,
        'appreciation_text' => 'My private note.',
        'struggle_text' => 'My private struggle.',
        'intention_text' => 'My private intention.',
    ]);

    WeeklyCheckIn::query()->create([
        'user_id' => $partner->id,
        'couple_id' => $couple->id,
        'week_start_date' => '2026-03-02',
        'emotional_availability' => 1,
        'communication_effort' => 1,
        'stress_level' => 5,
        'appreciation_text' => 'Partners note should stay hidden.',
    ]);

    $this->actingAs($user)
        ->get(route('checkins.weekly.history'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('CheckIn/WeeklyHistory')
            ->has('history.data', 1)
            ->where('history.data.0.appreciationText', 'My private note.')
            ->missing('history.data.1')
        )
        ->assertDontSee('Partners note should stay hidden.');
});

function weeklyCheckInPayload(array $overrides = []): array
{
    return array_merge([
        'emotional_availability' => 4,
        'communication_effort' => 4,
        'stress_level' => 2,
        'appreciation_text' => 'I appreciated our steadiness.',
        'struggle_text' => 'I felt stretched in the middle of the week.',
        'intention_text' => 'I want to slow down before reacting.',
    ], $overrides);
}

function activeCoupleFor(User ...$users): Couple
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

function pendingCoupleFor(User $user): Couple
{
    $couple = Couple::factory()->create([
        'status' => CoupleStatus::Pending,
    ]);

    CoupleMembership::query()->create([
        'couple_id' => $couple->id,
        'user_id' => $user->id,
        'slot' => 1,
        'joined_at' => now(),
        'is_active' => true,
    ]);

    return $couple;
}
