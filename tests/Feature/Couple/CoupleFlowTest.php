<?php

use App\Domains\Couple\Models\Couple;
use App\Domains\Couple\Models\CoupleInvite;
use App\Models\User;

test('authenticated users can create a couple space', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('couple.store'));

    $response->assertRedirect(route('couple.pending', absolute: false));

    $this->assertDatabaseHas('couples', [
        'status' => 'pending',
    ]);

    $this->assertDatabaseHas('couple_memberships', [
        'user_id' => $user->id,
        'slot' => 1,
        'is_active' => true,
    ]);
});

test('pending couple members can create an invite', function () {
    $user = User::factory()->create();
    $this->actingAs($user)->post(route('couple.store'));

    $response = $this->actingAs($user)->post(route('couple.invite.store'), [
        'email' => 'partner@example.com',
    ]);

    $response->assertRedirect(route('couple.pending', absolute: false));
    $this->assertDatabaseHas('couple_invites', [
        'email' => 'partner@example.com',
    ]);
});

test('a second user can accept an invite and activate the couple space', function () {
    $owner = User::factory()->create();
    $partner = User::factory()->create();

    $this->actingAs($owner)->post(route('couple.store'));
    $this->actingAs($owner)->post(route('couple.invite.store'), [
        'email' => $partner->email,
    ]);

    $invite = CoupleInvite::query()->firstOrFail();

    $response = $this->actingAs($partner)->post(route('invite.accept', $invite->token));

    $response->assertRedirect(route('couple.show', absolute: false));

    expect(Couple::query()->firstOrFail()->status->value)->toBe('active');

    $this->assertDatabaseHas('couple_memberships', [
        'user_id' => $partner->id,
        'slot' => 2,
        'is_active' => true,
    ]);
});

test('users already in another current couple cannot accept a new invite', function () {
    $owner = User::factory()->create();
    $existing = User::factory()->create();
    $partner = User::factory()->create();

    $this->actingAs($owner)->post(route('couple.store'));
    $this->actingAs($owner)->post(route('couple.invite.store'), [
        'email' => $partner->email,
    ]);
    $invite = CoupleInvite::query()->latest('id')->firstOrFail();

    $this->actingAs($existing)->post(route('couple.store'));

    $response = $this
        ->actingAs($existing)
        ->post(route('invite.accept', $invite->token));

    $response->assertForbidden();
    $this->assertDatabaseMissing('couple_memberships', [
        'user_id' => $existing->id,
        'couple_id' => $invite->couple_id,
    ]);
});

test('either member can dissolve an active couple space', function () {
    $owner = User::factory()->create();
    $partner = User::factory()->create();

    $this->actingAs($owner)->post(route('couple.store'));
    $this->actingAs($owner)->post(route('couple.invite.store'), [
        'email' => $partner->email,
    ]);
    $invite = CoupleInvite::query()->firstOrFail();
    $this->actingAs($partner)->post(route('invite.accept', $invite->token));

    $response = $this->actingAs($partner)->post(route('couple.dissolve'));

    $response->assertRedirect(route('couple.dissolved', absolute: false));
    $this->assertDatabaseHas('couples', [
        'id' => $invite->couple_id,
        'status' => 'dissolved',
    ]);
});
