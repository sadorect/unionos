<?php

use App\Domains\CheckIn\Models\MonthlyCoupleCheckIn;
use App\Domains\Couple\Enums\CoupleStatus;
use App\Domains\Couple\Models\Couple;
use App\Domains\Couple\Models\CoupleMembership;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    Carbon::setTestNow(CarbonImmutable::parse('2026-03-10 12:00:00'));
});

afterEach(function () {
    Carbon::setTestNow();
});

test('active couple members can submit one monthly check-in and are sent to waiting', function () {
    $user = User::factory()->create();
    activeMonthlyCoupleFor($user, User::factory()->create());

    $response = $this->actingAs($user)->post(route('checkins.monthly.store'), monthlyPayload());

    $response->assertRedirect(route('checkins.monthly.waiting', absolute: false));

    $submission = MonthlyCoupleCheckIn::query()->firstOrFail();
    expect($submission->user_id)->toBe($user->id);
    expect($submission->month)->toBe('2026-03');
    expect($submission->felt_heard)->toBe(4);
});

test('users cannot submit the monthly check-in twice in the same month', function () {
    $user = User::factory()->create();
    activeMonthlyCoupleFor($user, User::factory()->create());

    $this->actingAs($user)->post(route('checkins.monthly.store'), monthlyPayload());

    $response = $this->actingAs($user)->post(route('checkins.monthly.store'), monthlyPayload());

    $response->assertSessionHasErrors('monthly');
    expect(MonthlyCoupleCheckIn::query()->count())->toBe(1);
});

test('monthly route redirects to insight only after both partners submit', function () {
    $user = User::factory()->create();
    $partner = User::factory()->create();
    $couple = activeMonthlyCoupleFor($user, $partner);

    MonthlyCoupleCheckIn::query()->create([
        'user_id' => $user->id,
        'couple_id' => $couple->id,
        'month' => '2026-03',
        'felt_heard' => 4,
        'felt_appreciated' => 4,
        'conflict_safety' => 4,
        'emotional_closeness' => 4,
        'submitted_at' => now(),
    ]);

    $this->actingAs($user)
        ->get(route('checkins.monthly.show'))
        ->assertRedirect(route('checkins.monthly.waiting', absolute: false));

    MonthlyCoupleCheckIn::query()->create([
        'user_id' => $partner->id,
        'couple_id' => $couple->id,
        'month' => '2026-03',
        'felt_heard' => 2,
        'felt_appreciated' => 4,
        'conflict_safety' => 1,
        'emotional_closeness' => 4,
        'submitted_at' => now(),
    ]);

    $this->actingAs($user)
        ->get(route('checkins.monthly.show'))
        ->assertRedirect(route('checkins.monthly.insight', absolute: false));
});

test('monthly insight exposes only merged classifications and not raw scores', function () {
    $user = User::factory()->create();
    $partner = User::factory()->create();
    $couple = activeMonthlyCoupleFor($user, $partner);

    MonthlyCoupleCheckIn::query()->create([
        'user_id' => $user->id,
        'couple_id' => $couple->id,
        'month' => '2026-03',
        'felt_heard' => 5,
        'felt_appreciated' => 3,
        'conflict_safety' => 2,
        'emotional_closeness' => 5,
        'submitted_at' => now(),
    ]);

    MonthlyCoupleCheckIn::query()->create([
        'user_id' => $partner->id,
        'couple_id' => $couple->id,
        'month' => '2026-03',
        'felt_heard' => 4,
        'felt_appreciated' => 1,
        'conflict_safety' => 4,
        'emotional_closeness' => 5,
        'submitted_at' => now(),
    ]);

    $this->actingAs($user)
        ->get(route('checkins.monthly.insight'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('CheckIn/MonthlyInsight')
            ->has('cards', 4)
            ->where('cards.0.state', 'aligned')
            ->where('cards.1.state', 'needs conversation')
            ->missing('cards.0.felt_heard')
            ->missing('cards.1.felt_appreciated')
        );
});

function monthlyPayload(array $overrides = []): array
{
    return array_merge([
        'felt_heard' => 4,
        'felt_appreciated' => 4,
        'conflict_safety' => 4,
        'emotional_closeness' => 4,
    ], $overrides);
}

function activeMonthlyCoupleFor(User ...$users): Couple
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
