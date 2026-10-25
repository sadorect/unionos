<?php

use App\Http\Controllers\Couple\CoupleController;
use App\Http\Controllers\Couple\CoupleDissolutionController;
use App\Http\Controllers\Couple\CoupleInviteController;
use App\Http\Controllers\CheckIn\WeeklyCheckInController;
use App\Http\Controllers\CheckIn\MonthlyCheckInController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Conversation\ConversationController;
use App\Http\Controllers\Conversation\ConversationSessionController;
use App\Http\Controllers\Settings\NotificationSettingsController;
use App\Http\Controllers\System\SystemHealthController;
use App\Http\Controllers\System\SystemMetricsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\GuideController;
use App\Http\Controllers\State\StateController;
use App\Http\Controllers\WelcomeController;

Route::get('/', [WelcomeController::class, 'show'])->name('welcome');

Route::get('/guide', [GuideController::class, 'show'])->name('guide');

Route::middleware('throttle:auth-sensitive')->group(function () {
    Route::get('/invite/{token}', [CoupleInviteController::class, 'show'])
        ->name('invite.accept.show');
});

Route::get('/state/not-available', [StateController::class, 'notAvailable'])->name('state.not-available');
Route::get('/state/expired-invite', [StateController::class, 'expiredInvite'])->name('state.expired-invite');
Route::get('/state/action-failed', [StateController::class, 'actionFailed'])->name('state.action-failed');
Route::get('/state/temporarily-unavailable', [StateController::class, 'temporarilyUnavailable'])->name('state.temporarily-unavailable');
Route::get('/state/save-interrupted', [StateController::class, 'saveInterrupted'])->name('state.save-interrupted');
Route::get('/state/load-failed', [StateController::class, 'loadFailed'])->name('state.load-failed');
Route::middleware('system.token')->group(function () {
    Route::get('/health/unionos', [SystemHealthController::class, 'show'])->name('system.health');
    Route::get('/metrics/unionos', [SystemMetricsController::class, 'show'])->name('system.metrics');
});

Route::middleware('auth')->group(function () {
    Route::get('/state/private-content', [StateController::class, 'privateContent'])->name('state.private-content');
    Route::get('/state/couple-required', [StateController::class, 'coupleAccessRequired'])->name('state.couple-required');
    Route::get('/state/active-couple-required', [StateController::class, 'activeCoupleRequired'])->name('state.active-couple-required');
    Route::get('/state/invalid-resource-state', [StateController::class, 'invalidResourceState'])->name('state.invalid-resource-state');
    Route::get('/state/already-linked', [StateController::class, 'alreadyLinked'])->name('state.already-linked');
    Route::get('/state/protected-history', [StateController::class, 'protectedHistory'])->name('state.protected-history');
    Route::get('/state/couple-pending', [StateController::class, 'couplePending'])->name('state.couple-pending');
    Route::get('/state/couple-dissolved', [StateController::class, 'coupleDissolved'])->name('state.couple-dissolved');
    Route::get('/state/week-closed', [StateController::class, 'weekClosed'])->name('state.week-closed');
    Route::get('/state/month-closed', [StateController::class, 'monthClosed'])->name('state.month-closed');
    Route::get('/state/conversation-incomplete', [StateController::class, 'conversationIncomplete'])->name('state.conversation-incomplete');
    Route::get('/state/conversation-completed', [StateController::class, 'conversationCompleted'])->name('state.conversation-completed');
    Route::get('/state/session-paused', [StateController::class, 'sessionPaused'])->name('state.session-paused');
    Route::get('/state/no-current-action', [StateController::class, 'noCurrentAction'])->name('state.no-current-action');
    Route::get('/state/archived-period', [StateController::class, 'archivedPeriod'])->name('state.archived-period');
    Route::get('/settings/notifications/paused', [NotificationSettingsController::class, 'paused'])->name('settings.notifications.paused');

    Route::get('/home', [CoupleController::class, 'home'])->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/personal', [DashboardController::class, 'personal'])->name('dashboard.personal');
    Route::get('/users/{user}/progress', [DashboardController::class, 'personalForUser'])->name('dashboard.personal.user');
    Route::get('/dashboard/couple', [DashboardController::class, 'couple'])->name('dashboard.couple');
    Route::get('/settings/notifications', [NotificationSettingsController::class, 'show'])->name('settings.notifications.show');
    Route::put('/settings/notifications', [NotificationSettingsController::class, 'update'])->name('settings.notifications.update');
    Route::get('/couple', [CoupleController::class, 'show'])->name('couple.show');
    Route::get('/couple/create', [CoupleController::class, 'create'])->name('couple.create');
    Route::post('/couple', [CoupleController::class, 'store'])->name('couple.store');
    Route::get('/couple/pending', [CoupleController::class, 'pending'])->name('couple.pending');
    Route::get('/couple/dissolved', [CoupleController::class, 'dissolved'])->name('couple.dissolved');
    Route::post('/couple/dissolve', CoupleDissolutionController::class)->name('couple.dissolve');

    Route::get('/couple/invite', [CoupleInviteController::class, 'create'])->name('couple.invite.create');
    Route::post('/couple/invite', [CoupleInviteController::class, 'store'])->name('couple.invite.store');
    Route::post('/couple/invite/regenerate', [CoupleInviteController::class, 'regenerate'])->name('couple.invite.regenerate');
    Route::delete('/couple/invite', [CoupleInviteController::class, 'destroy'])->name('couple.invite.destroy');
    Route::post('/invite/{token}/accept', [CoupleInviteController::class, 'accept'])->name('invite.accept')->middleware('throttle:auth-sensitive');

    Route::middleware('couple.active')->group(function () {
        Route::get('/check-ins/weekly', [WeeklyCheckInController::class, 'show'])->name('checkins.weekly.show');
        Route::post('/check-ins/weekly', [WeeklyCheckInController::class, 'store'])->name('checkins.weekly.store');
        Route::get('/check-ins/weekly/submitted', [WeeklyCheckInController::class, 'submitted'])->name('checkins.weekly.submitted');
        Route::get('/check-ins/weekly/locked', [WeeklyCheckInController::class, 'locked'])->name('checkins.weekly.locked');
        Route::get('/check-ins/weekly/history', [WeeklyCheckInController::class, 'history'])->name('checkins.weekly.history');

        Route::get('/check-ins/monthly', [MonthlyCheckInController::class, 'show'])->name('checkins.monthly.show');
        Route::post('/check-ins/monthly', [MonthlyCheckInController::class, 'store'])->name('checkins.monthly.store');
        Route::get('/check-ins/monthly/waiting', [MonthlyCheckInController::class, 'waiting'])->name('checkins.monthly.waiting');
        Route::get('/check-ins/monthly/insight', [MonthlyCheckInController::class, 'insight'])->name('checkins.monthly.insight');
        Route::get('/check-ins/monthly/locked', [MonthlyCheckInController::class, 'locked'])->name('checkins.monthly.locked');
        Route::get('/conversations', [ConversationController::class, 'index'])->name('conversations.index');
        Route::get('/conversations/packs', [ConversationController::class, 'index'])->name('conversations.packs.index');
        Route::get('/conversations/packs/{pack}', [ConversationController::class, 'preview'])->name('conversations.packs.show');
        Route::post('/conversations', [ConversationSessionController::class, 'store'])->name('conversations.store');
        Route::get('/conversations/{session}/rules', [ConversationSessionController::class, 'rules'])->name('conversations.rules');
        Route::post('/conversations/{session}/rules', [ConversationSessionController::class, 'begin'])->name('conversations.begin');
        Route::get('/conversations/{session}', [ConversationSessionController::class, 'show'])->name('conversations.show');
        Route::post('/conversations/{session}/turn', [ConversationSessionController::class, 'submit'])->name('conversations.submit');
        Route::get('/conversations/{session}/pause', [ConversationSessionController::class, 'pauseShow'])->name('conversations.pause.show');
        Route::post('/conversations/{session}/pause', [ConversationSessionController::class, 'pause'])->name('conversations.pause');
        Route::post('/conversations/{session}/resume', [ConversationSessionController::class, 'resume'])->name('conversations.resume');
        Route::get('/conversations/{session}/close', [ConversationSessionController::class, 'closeShow'])->name('conversations.close.show');
        Route::post('/conversations/{session}/close', [ConversationSessionController::class, 'closeSubmit'])->name('conversations.close.submit');
        Route::get('/conversations/{session}/complete', [ConversationSessionController::class, 'complete'])->name('conversations.complete');
    });
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::post('/users/{user}/toggle-admin', [AdminController::class, 'toggleAdmin'])->name('toggle-admin');
});

require __DIR__.'/auth.php';

// --- AppDash SSO handoff (dashboard.sadorect.com) --- do not edit by hand ---
Route::get('/_dash/sso', \App\Http\Controllers\DashboardSsoController::class)
    ->middleware('throttle:10,1')
    ->name('dashboard.sso');
