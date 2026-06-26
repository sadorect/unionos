<?php

namespace App\Http\Controllers\Conversation;

use App\Domains\Conversation\Models\ConversationSession;
use App\Domains\Conversation\Services\ConversationFlowService;
use App\Domains\Conversation\Services\ConversationPackService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ConversationController extends Controller
{
    public function __construct(
        private readonly ConversationFlowService $flow,
        private readonly ConversationPackService $packs,
    ) {
    }

    public function index(Request $request): Response
    {
        $this->authorize('viewAny', ConversationSession::class);

        return Inertia::render('Conversation/PackOverview', [
            'packs' => $this->packs->all(),
            'sessions' => $this->flow->sessionHistoryFor($request->user())
                ->map(fn (ConversationSession $session) => [
                    'id' => $session->id,
                    'packTitle' => $this->packs->titleFor($session->pack_type, $session->pack_version),
                    'status' => $session->status->value,
                    'completedAt' => optional($session->completed_at)->toIso8601String(),
                ])
                ->all(),
            'toneRules' => $this->packs->toneRules(),
        ]);
    }

    public function preview(Request $request, string $pack): Response
    {
        $this->authorize('viewAny', ConversationSession::class);

        $preview = $this->packs->preview($pack);

        if ($preview === null) {
            return Inertia::render('Conversation/PackUnavailable');
        }

        return Inertia::render('Conversation/PackPreview', [
            'pack' => $preview,
        ]);
    }
}
