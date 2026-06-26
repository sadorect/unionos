<?php

use App\Domains\CheckIn\Models\MonthlyCoupleCheckIn;
use App\Domains\CheckIn\Models\WeeklyCheckIn;
use App\Domains\Conversation\Models\ConversationSession;
use App\Domains\Couple\Enums\CoupleStatus;
use App\Domains\Couple\Models\Couple;
use App\Domains\Couple\Models\CoupleMembership;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('personal dashboard renders private reflection summaries for the signed in user', function () {
    $user = User::factory()->create();
    $partner = User::factory()->create();
    $couple = activeDashboardCoupleFor($user, $partner);

    WeeklyCheckIn::query()->create([
        'user_id' => $user->id,
        'couple_id' => $couple->id,
        'week_start_date' => now()->startOfWeek(),
        'emotional_availability' => 4,
        'communication_effort' => 3,
        'stress_level' => 2,
        'appreciation_text' => 'Private note.',
    ]);

    ConversationSession::query()->create([
        'couple_id' => $couple->id,
        'started_by_user_id' => $user->id,
        'current_turn_user_id' => null,
        'pack_type' => 'weekly-reset',
        'pack_version' => 1,
        'status' => 'completed',
        'ground_rules_confirmed_at' => now(),
        'started_at' => now(),
        'completed_at' => now(),
    ]);

    $this->actingAs($user)
        ->get(route('dashboard.personal'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Dashboard/Personal')
            ->where('isEmpty', false)
            ->where('overviewCards.0.value', '1 completed')
            ->where('conversationActivity.0.packTitle', 'Weekly Reset')
            ->missing('conversationActivity.1')
        );
});

test('personal dashboard shows an empty state for users with no reflection history', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('dashboard.personal'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Dashboard/Personal')
            ->where('isEmpty', true)
        );
});

test('couple dashboard renders safe shared aggregates without raw monthly answers', function () {
    $user = User::factory()->create();
    $partner = User::factory()->create();
    $couple = activeDashboardCoupleFor($user, $partner);

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

    MonthlyCoupleCheckIn::query()->create([
        'user_id' => $partner->id,
        'couple_id' => $couple->id,
        'month' => '2026-03',
        'felt_heard' => 2,
        'felt_appreciated' => 4,
        'conflict_safety' => 4,
        'emotional_closeness' => 4,
        'submitted_at' => now(),
    ]);

    ConversationSession::query()->create([
        'couple_id' => $couple->id,
        'started_by_user_id' => $partner->id,
        'current_turn_user_id' => null,
        'pack_type' => 'feeling-unheard',
        'pack_version' => 1,
        'status' => 'completed',
        'ground_rules_confirmed_at' => now(),
        'started_at' => now(),
        'completed_at' => now(),
    ]);

    $this->actingAs($user)
        ->get(route('dashboard.couple'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Dashboard/Couple')
            ->where('isEmpty', false)
            ->where('overviewCards.2.value', 'Mixed')
            ->where('alignmentRows.0.status', 'Mixed')
            ->where('conversationActivity.0.packTitle', 'Feeling Unheard')
            ->missing('alignmentRows.0.felt_heard')
        );
});

function activeDashboardCoupleFor(User ...$users): Couple
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
