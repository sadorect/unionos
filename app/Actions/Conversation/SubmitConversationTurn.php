<?php

namespace App\Actions\Conversation;

use App\Events\FeatureActivityOccurred;
use App\Domains\Conversation\Enums\ConversationSessionStatus;
use App\Domains\Conversation\Models\ConversationSession;
use App\Domains\Conversation\Models\ConversationTurn;
use App\Domains\Conversation\Services\ConversationFlowService;
use App\Models\User;

class SubmitConversationTurn
{
    public function __construct(
        private readonly ConversationFlowService $flow,
    ) {
    }

    public function handle(ConversationSession $session, User $user, string $responseText): ConversationSession
    {
        $step = $this->flow->currentStep($session);

        ConversationTurn::query()->create([
            'session_id' => $session->id,
            'user_id' => $user->id,
            'prompt_key' => $step['prompt_key'],
            'response_text' => $responseText,
            'created_at' => now(),
        ]);

        $session->refresh();
        $nextTurnUserId = $this->flow->nextTurnUserId($session);

        $session->forceFill([
            'current_turn_user_id' => $nextTurnUserId,
            'last_activity_at' => now(),
            'status' => $nextTurnUserId === null
                ? ConversationSessionStatus::Completed->value
                : ConversationSessionStatus::Active->value,
            'completed_at' => $nextTurnUserId === null ? now() : null,
        ])->save();

        event(new FeatureActivityOccurred('conversation_session', $nextTurnUserId === null ? 'completed' : 'progressed', [
            'status' => $nextTurnUserId === null ? 'completed' : 'active',
            'pack_type' => $session->pack_type,
            'pack_version' => $session->pack_version,
        ]));

        return $session->refresh();
    }
}
