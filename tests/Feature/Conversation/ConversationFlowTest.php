<?php

use App\Domains\Conversation\Models\ConversationSession;
use App\Domains\Couple\Enums\CoupleStatus;
use App\Domains\Couple\Models\Couple;
use App\Domains\Couple\Models\CoupleMembership;
use App\Models\User;
use Illuminate\Support\Facades\Config;
use Inertia\Testing\AssertableInertia as Assert;

test('active couple members can start a guided conversation and land on ground rules', function () {
    $user = User::factory()->create();
    activeConversationCoupleFor($user, User::factory()->create());

    $response = $this->actingAs($user)->post(route('conversations.store'), [
        'pack_type' => 'weekly-reset',
    ]);

    $session = ConversationSession::query()->firstOrFail();

    $response->assertRedirect(route('conversations.rules', $session, absolute: false));
    expect($session->pack_type)->toBe('weekly-reset');
});

test('beginning a session confirms ground rules and sets the first turn', function () {
    [$user] = conversationMembers();

    $session = $this->actingAs($user)->post(route('conversations.store'), [
        'pack_type' => 'weekly-reset',
    ]);

    $conversation = ConversationSession::query()->firstOrFail();

    $response = $this->actingAs($user)->post(route('conversations.begin', $conversation));

    $response->assertRedirect(route('conversations.show', $conversation, absolute: false));

    $conversation->refresh();
    expect($conversation->ground_rules_confirmed_at)->not->toBeNull();
    expect($conversation->current_turn_user_id)->toBe($user->id);
});

test('submitting a turn passes the session to the next speaker', function () {
    [$user, $partner, $session] = startedConversation();

    $response = $this->actingAs($user)->post(route('conversations.submit', $session), [
        'response_text' => 'One thing that went well for me this week was a calmer start to Monday.',
    ]);

    $response->assertRedirect(route('conversations.show', $session, absolute: false));

    $session->refresh();
    expect($session->turns)->toHaveCount(1);
    expect($session->current_turn_user_id)->toBe($partner->id);
});

test('conversation sessions can be paused and resumed', function () {
    [$user, $partner, $session] = startedConversation();

    $this->actingAs($user)->post(route('conversations.submit', $session), [
        'response_text' => 'First response.',
    ]);

    $pause = $this->actingAs($partner)->post(route('conversations.pause', $session));
    $pause->assertRedirect(route('conversations.pause.show', $session, absolute: false));

    $session->refresh();
    expect($session->status->value)->toBe('paused');

    $resume = $this->actingAs($partner)->post(route('conversations.resume', $session));
    $resume->assertRedirect(route('conversations.show', $session, absolute: false));

    expect($session->fresh()->status->value)->toBe('active');
});

test('weekly reset sessions complete after all prompt and closing turns are submitted', function () {
    [$user, $partner, $session] = startedConversation();

    $turns = [
        [$user, 'A steady start to the week helped me.'],
        [$partner, 'What I hear is that you felt less rushed.'],
        [$user, 'I want more quiet time before dinner next week.'],
        [$partner, 'I can help protect that by handling cleanup first.'],
        [$user, 'I appreciate how you kept the evening calm.'],
        [$partner, 'I appreciate how clearly you named what you needed.'],
    ];

    foreach ($turns as [$actor, $text]) {
        $route = $session->fresh()->current_turn_user_id === $actor->id && $session->fresh()->turns()->count() >= 4
            ? route('conversations.close.submit', $session)
            : route('conversations.submit', $session);

        $this->actingAs($actor)->post($route, [
            'response_text' => $text,
        ]);
    }

    $session->refresh();
    expect($session->status->value)->toBe('completed');
    expect($session->completed_at)->not->toBeNull();
    expect($session->turns)->toHaveCount(6);
});

test('conversation index renders available packs and recent session history', function () {
    [$user] = conversationMembers();

    $this->actingAs($user)->post(route('conversations.store'), [
        'pack_type' => 'weekly-reset',
    ]);

    $this->actingAs($user)
        ->get(route('conversations.index'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Conversation/PackOverview')
            ->has('packs', 3)
            ->has('sessions', 1)
            ->where('sessions.0.packTitle', 'Weekly Reset')
        );
});

test('active packs can be previewed before starting a conversation', function () {
    [$user] = conversationMembers();

    $this->actingAs($user)
        ->get(route('conversations.packs.show', 'weekly-reset'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Conversation/PackPreview')
            ->where('pack.title', 'Weekly Reset')
            ->where('pack.promptCount', 4)
            ->where('pack.prompts.0.label', 'Reset the week')
        );
});

test('missing active pack previews fail to the unavailable page', function () {
    [$user] = conversationMembers();

    $this->actingAs($user)
        ->get(route('conversations.packs.show', 'missing-pack'))
        ->assertInertia(fn (Assert $page) => $page->component('Conversation/PackUnavailable'));
});

test('live sessions stay on their fixed content version when a newer version becomes active', function () {
    [$user, $partner, $session] = startedConversation();

    Config::set('unionos_content.conversation_packs.weekly-reset.active_version', 2);
    Config::set('unionos_content.conversation_packs.weekly-reset.versions.2', [
        'slug' => 'weekly-reset',
        'version' => 2,
        'is_active' => true,
        'title' => 'Weekly Reset',
        'description' => 'A light conversation to reconnect and reset the week.',
        'purpose_tag' => 'Reconnection',
        'duration' => '10-15 min',
        'prompts' => [
            [
                'key' => 'weekly-reset-1',
                'label' => 'Reset the week',
                'speaker' => 'slot_1',
                'text' => 'A newer prompt that should not appear in this session.',
                'instruction' => 'Stay grounded.',
                'timer_seconds' => 180,
            ],
        ],
        'closing_prompt' => [
            'key' => 'weekly-reset-close',
            'label' => 'Close with appreciation',
            'speaker' => 'both',
            'text' => 'One thing I appreciate about you right now...',
            'instruction' => 'Keep it brief.',
            'timer_seconds' => 120,
        ],
    ]);

    $this->actingAs($user)
        ->get(route('conversations.show', $session))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Conversation/PromptStep')
            ->where('session.hasVersionNotice', true)
            ->where('step.promptText', 'One thing that went well for me this week...')
        );
});

function conversationMembers(): array
{
    $user = User::factory()->create();
    $partner = User::factory()->create();
    activeConversationCoupleFor($user, $partner);

    return [$user, $partner];
}

function startedConversation(): array
{
    [$user, $partner] = conversationMembers();

    test()->actingAs($user)->post(route('conversations.store'), [
        'pack_type' => 'weekly-reset',
    ]);

    $session = ConversationSession::query()->firstOrFail();
    test()->actingAs($user)->post(route('conversations.begin', $session));

    return [$user, $partner, $session->fresh()];
}

function activeConversationCoupleFor(User ...$users): Couple
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
