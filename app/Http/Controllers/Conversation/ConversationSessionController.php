<?php

namespace App\Http\Controllers\Conversation;

use App\Actions\Conversation\BeginConversationSession;
use App\Actions\Conversation\PauseConversationSession;
use App\Actions\Conversation\ResumeConversationSession;
use App\Actions\Conversation\StartConversationSession;
use App\Actions\Conversation\SubmitConversationTurn;
use App\Domains\Conversation\Enums\ConversationSessionStatus;
use App\Domains\Conversation\Models\ConversationSession;
use App\Domains\Conversation\Services\ConversationFlowService;
use App\Domains\Conversation\Services\ConversationPackService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Conversation\StartConversationSessionRequest;
use App\Http\Requests\Conversation\SubmitConversationTurnRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ConversationSessionController extends Controller
{
    public function __construct(
        private readonly ConversationFlowService $flow,
        private readonly ConversationPackService $packs,
        private readonly StartConversationSession $startConversationSession,
        private readonly BeginConversationSession $beginConversationSession,
        private readonly SubmitConversationTurn $submitConversationTurn,
        private readonly PauseConversationSession $pauseConversationSession,
        private readonly ResumeConversationSession $resumeConversationSession,
    ) {
    }

    public function store(StartConversationSessionRequest $request): RedirectResponse
    {
        $this->authorize('create', ConversationSession::class);

        $session = $this->startConversationSession->handle(
            $request->user(),
            $this->flow->activeCoupleFor($request->user()),
            $request->validated('pack_type'),
        );

        return redirect()->route('conversations.rules', $session);
    }

    public function rules(Request $request, ConversationSession $session): Response|RedirectResponse
    {
        $this->authorize('view', $session);

        if ($this->flow->hasStarted($session)) {
            return redirect()->route('conversations.show', $session);
        }

        return Inertia::render('Conversation/GroundRules', [
            'session' => $this->sessionPayload($session),
        ]);
    }

    public function begin(Request $request, ConversationSession $session): RedirectResponse
    {
        $this->authorize('update', $session);
        $this->beginConversationSession->handle($session);

        return redirect()->route('conversations.show', $session);
    }

    public function show(Request $request, ConversationSession $session): Response|RedirectResponse
    {
        $this->authorize('view', $session);

        if ($this->packs->forSession($session) === null) {
            return Inertia::render('Conversation/PackUnavailable');
        }

        if (! $this->flow->hasStarted($session)) {
            return redirect()->route('conversations.rules', $session);
        }

        if ($session->status === ConversationSessionStatus::Paused) {
            return redirect()->route('state.session-paused', [
                'id' => $session->id,
                'packTitle' => $this->packs->find($session->pack_type)['title'],
            ]);
        }

        if ($session->status === ConversationSessionStatus::Completed) {
            return redirect()->route('state.conversation-completed', [
                'packTitle' => $this->packs->find($session->pack_type)['title'],
            ]);
        }

        if ($session->status === ConversationSessionStatus::Abandoned) {
            return redirect()->route('state.conversation-incomplete', [
                'packTitle' => $this->packs->find($session->pack_type)['title'],
            ]);
        }

        if ($this->flow->isClosingStep($session)) {
            return redirect()->route('conversations.close.show', $session);
        }

        return Inertia::render('Conversation/PromptStep', [
            'session' => $this->sessionPayload($session),
            'step' => $this->stepPayload($session, $request->user()),
            'isParticipantsTurn' => $this->flow->isParticipantsTurn($session, $request->user()),
        ]);
    }

    public function submit(SubmitConversationTurnRequest $request, ConversationSession $session): RedirectResponse
    {
        $this->authorize('update', $session);
        abort_unless($this->flow->isParticipantsTurn($session, $request->user()), 403);

        $this->submitConversationTurn->handle($session, $request->user(), $request->validated('response_text'));

        return redirect()->route('conversations.show', $session);
    }

    public function pauseShow(Request $request, ConversationSession $session): Response
    {
        $this->authorize('view', $session);

        return Inertia::render('State/SessionPaused', [
            'session' => $this->sessionPayload($session),
        ]);
    }

    public function pause(Request $request, ConversationSession $session): RedirectResponse
    {
        $this->authorize('update', $session);
        $this->pauseConversationSession->handle($session);

        return redirect()->route('conversations.pause.show', $session);
    }

    public function resume(Request $request, ConversationSession $session): RedirectResponse
    {
        $this->authorize('update', $session);
        $this->resumeConversationSession->handle($session);

        return redirect()->route('conversations.show', $session);
    }

    public function closeShow(Request $request, ConversationSession $session): Response|RedirectResponse
    {
        $this->authorize('view', $session);

        if (! $this->flow->isClosingStep($session)) {
            return redirect()->route('conversations.show', $session);
        }

        return Inertia::render('Conversation/AppreciationClose', [
            'session' => $this->sessionPayload($session),
            'step' => $this->stepPayload($session, $request->user()),
            'isParticipantsTurn' => $this->flow->isParticipantsTurn($session, $request->user()),
        ]);
    }

    public function closeSubmit(SubmitConversationTurnRequest $request, ConversationSession $session): RedirectResponse
    {
        $this->authorize('update', $session);
        abort_unless($this->flow->isParticipantsTurn($session, $request->user()), 403);

        $this->submitConversationTurn->handle($session, $request->user(), $request->validated('response_text'));

        return redirect()->route('conversations.complete', $session);
    }

    public function complete(Request $request, ConversationSession $session): Response|RedirectResponse
    {
        $this->authorize('view', $session);

        if ($session->status !== ConversationSessionStatus::Completed) {
            return redirect()->route('conversations.show', $session);
        }

        return Inertia::render('Conversation/Complete', [
            'session' => $this->sessionPayload($session),
        ]);
    }

    private function sessionPayload(ConversationSession $session): array
    {
        $pack = $this->packs->forSession($session);

        return [
            'id' => $session->id,
            'packType' => $session->pack_type,
            'packTitle' => $pack?->title ?? $session->pack_type,
            'packVersion' => $session->pack_version,
            'status' => $session->status->value,
            'progressLabel' => sprintf(
                'Step %d of %d',
                min($this->flow->completedStepCount($session) + 1, $this->flow->totalStepCount($session)),
                $this->flow->totalStepCount($session),
            ),
            'pausedAt' => optional($session->paused_at)->toIso8601String(),
            'completedAt' => optional($session->completed_at)->toIso8601String(),
            'hasVersionNotice' => $this->packs->sessionUsesNonActiveVersion($session),
        ];
    }

    private function stepPayload(ConversationSession $session, User $user): array
    {
        $step = $this->flow->currentStep($session);

        return [
            'label' => $step['prompt_label'] ?? '',
            'promptText' => $step['prompt_text'] ?? '',
            'instruction' => $step['instruction'] ?? '',
            'timerSeconds' => $step['timer_seconds'] ?? null,
            'turnLabel' => $step['turn_label'] ?? '',
            'speakerState' => $step['user_id'] === $user->id ? 'your_turn' : 'partner_turn',
        ];
    }
}
