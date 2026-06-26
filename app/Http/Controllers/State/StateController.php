<?php

namespace App\Http\Controllers\State;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class StateController extends Controller
{
    public function notAvailable(): Response
    {
        return Inertia::render('State/NotAvailable');
    }

    public function privateContent(): Response
    {
        return Inertia::render('State/PrivateContent');
    }

    public function coupleAccessRequired(): Response
    {
        return Inertia::render('State/CoupleAccessRequired');
    }

    public function activeCoupleRequired(Request $request): Response
    {
        return Inertia::render('State/ActiveCoupleRequired', [
            'state' => (string) $request->query('state', 'pending'),
        ]);
    }

    public function invalidResourceState(): Response
    {
        return Inertia::render('State/InvalidResourceState');
    }

    public function expiredInvite(): Response
    {
        return Inertia::render('State/ExpiredInvite');
    }

    public function alreadyLinked(): Response
    {
        return Inertia::render('State/AlreadyLinked');
    }

    public function protectedHistory(): Response
    {
        return Inertia::render('State/ProtectedHistory');
    }

    public function couplePending(): Response
    {
        return Inertia::render('State/CouplePending');
    }

    public function coupleDissolved(): Response
    {
        return Inertia::render('State/CoupleDissolved');
    }

    public function weekClosed(Request $request): Response
    {
        return Inertia::render('State/WeekClosed', [
            'week' => [
                'label' => (string) $request->query('label', 'This week'),
            ],
        ]);
    }

    public function monthClosed(Request $request): Response
    {
        return Inertia::render('State/MonthClosed', [
            'month' => [
                'label' => (string) $request->query('label', 'This month'),
            ],
            'hasInsight' => (bool) $request->boolean('hasInsight'),
        ]);
    }

    public function conversationIncomplete(Request $request): Response
    {
        return Inertia::render('State/ConversationIncomplete', [
            'session' => [
                'packTitle' => (string) $request->query('packTitle', 'Conversation'),
            ],
        ]);
    }

    public function conversationCompleted(Request $request): Response
    {
        return Inertia::render('State/ConversationCompleted', [
            'session' => [
                'packTitle' => (string) $request->query('packTitle', 'Conversation'),
            ],
        ]);
    }

    public function sessionPaused(Request $request): Response
    {
        return Inertia::render('State/SessionPaused', [
            'session' => [
                'id' => (int) $request->query('id'),
                'packTitle' => (string) $request->query('packTitle', 'Conversation'),
            ],
        ]);
    }

    public function noCurrentAction(): Response
    {
        return Inertia::render('State/NoCurrentAction');
    }

    public function archivedPeriod(Request $request): Response
    {
        return Inertia::render('State/ArchivedPeriod', [
            'label' => (string) $request->query('label', 'This period'),
        ]);
    }

    public function actionFailed(Request $request): Response
    {
        return Inertia::render('State/ActionFailed', [
            'retryUrl' => (string) $request->query('retry', route('home', absolute: false)),
        ]);
    }

    public function temporarilyUnavailable(Request $request): Response
    {
        return Inertia::render('State/TemporarilyUnavailable', [
            'retryUrl' => (string) $request->query('retry', url()->current()),
        ]);
    }

    public function saveInterrupted(Request $request): Response
    {
        return Inertia::render('State/SaveInterrupted', [
            'retryUrl' => (string) $request->query('retry', route('home', absolute: false)),
        ]);
    }

    public function loadFailed(Request $request): Response
    {
        return Inertia::render('State/LoadFailed', [
            'retryUrl' => (string) $request->query('retry', url()->current()),
        ]);
    }
}
