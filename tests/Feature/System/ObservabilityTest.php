<?php

use App\Events\FeatureActivityOccurred;
use App\Domains\Couple\Enums\CoupleStatus;
use App\Domains\Couple\Models\Couple;
use App\Domains\Couple\Models\CoupleMembership;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Inertia\Testing\AssertableInertia as Assert;

test('health endpoint returns privacy safe system status', function () {
    config(['unionos.system_token' => 'test-system-token']);

    $this->withToken('test-system-token')
        ->get(route('system.health'))
        ->assertOk()
        ->assertJsonStructure([
            'status',
            'checks' => [
                'database',
                'pending_jobs',
                'failed_jobs',
            ],
        ]);
});

test('health endpoint rejects requests without a valid token', function () {
    config(['unionos.system_token' => 'test-system-token']);

    $this->get(route('system.health'))->assertUnauthorized();
    $this->withToken('wrong-token')->get(route('system.health'))->assertUnauthorized();
});

test('metrics endpoint returns aggregate usage counts only', function () {
    config(['unionos.system_token' => 'test-system-token']);

    $this->withToken('test-system-token')
        ->get(route('system.metrics'))
        ->assertOk()
        ->assertJsonStructure([
            'usage' => [
                'weekly_check_ins_total',
                'monthly_check_ins_total',
                'conversation_sessions_total',
                'notifications_sent_total',
            ],
            'completion_rates' => [
                'weekly_current_week',
                'monthly_current_month',
                'conversation_completion',
            ],
            'system' => [
                'pending_jobs',
                'failed_jobs',
            ],
        ]);
});

test('weekly check-in saves dispatch privacy safe feature activity events', function () {
    Event::fake([FeatureActivityOccurred::class]);

    $user = User::factory()->create();
    $partner = User::factory()->create();
    $couple = activeObservabilityCoupleFor($user, $partner);

    $this->actingAs($user)->post(route('checkins.weekly.store'), [
        'emotional_availability' => 4,
        'communication_effort' => 4,
        'stress_level' => 2,
        'appreciation_text' => 'Private note that should not be logged.',
        'struggle_text' => 'Private struggle that should not be logged.',
        'intention_text' => 'Private intention that should not be logged.',
    ]);

    Event::assertDispatched(FeatureActivityOccurred::class, fn (FeatureActivityOccurred $event) => $event->feature === 'weekly_check_in' && $event->action === 'saved');
});

test('operational query failures on get requests render the load failed state', function () {
    Route::middleware(['web', 'auth'])->get('/__test/load-failed', function () {
        DB::table('missing_unionos_table')->get();
    });

    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/__test/load-failed')
        ->assertStatus(503)
        ->assertInertia(fn (Assert $page) => $page->component('State/LoadFailed'));
});

test('operational query failures on write requests redirect back with a retry notice', function () {
    Route::middleware(['web', 'auth'])->post('/__test/save-failed', function () {
        DB::table('missing_unionos_table')->insert(['id' => 1]);
    });

    $user = User::factory()->create();

    $this->actingAs($user)
        ->from(route('settings.notifications.show'))
        ->post('/__test/save-failed')
        ->assertRedirect(route('settings.notifications.show'))
        ->assertSessionHas('retryableError.message', 'Your changes may not have been saved. Please review and try again.');
});

function activeObservabilityCoupleFor(User ...$users): Couple
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
