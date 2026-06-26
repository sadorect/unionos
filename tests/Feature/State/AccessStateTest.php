<?php

use App\Domains\Couple\Enums\CoupleStatus;
use App\Domains\Couple\Models\Couple;
use App\Domains\Couple\Models\CoupleInvite;
use App\Domains\Couple\Models\CoupleMembership;
use App\Models\User;
use Illuminate\Support\Str;
use Inertia\Testing\AssertableInertia as Assert;

test('users without a couple are redirected to the couple access required state', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('checkins.weekly.show'))
        ->assertRedirect(route('state.couple-required', absolute: false));
});

test('partner attempts to view another users personal progress shows the private content state', function () {
    $owner = User::factory()->create();
    $viewer = User::factory()->create();

    $this->actingAs($viewer)
        ->get(route('dashboard.personal.user', $owner))
        ->assertInertia(fn (Assert $page) => $page->component('State/PrivateContent'));
});

test('expired invite tokens render the expired invite state', function () {
    $this->get(route('invite.accept.show', 'missing-token'))
        ->assertInertia(fn (Assert $page) => $page->component('State/ExpiredInvite'));
});

test('already linked users see the already linked state on invite access', function () {
    $owner = User::factory()->create();
    $existing = User::factory()->create();
    $couple = pendingStateCoupleFor($owner);
    $invite = CoupleInvite::query()->create([
        'couple_id' => $couple->id,
        'email' => 'jamie@example.com',
        'token' => Str::random(40),
        'expires_at' => now()->addDay(),
    ]);

    pendingStateCoupleFor($existing);

    $this->actingAs($existing)
        ->get(route('invite.accept.show', $invite->token))
        ->assertInertia(fn (Assert $page) => $page->component('State/AlreadyLinked'));
});

test('unknown authenticated pages render the not available state instead of a raw 404', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/missing-page')
        ->assertStatus(404)
        ->assertInertia(fn (Assert $page) => $page->component('State/NotAvailable'));
});

function pendingStateCoupleFor(User $user): Couple
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
