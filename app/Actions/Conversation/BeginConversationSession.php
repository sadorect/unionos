<?php

namespace App\Actions\Conversation;

use App\Domains\Conversation\Models\ConversationSession;
use App\Domains\Conversation\Services\ConversationFlowService;

class BeginConversationSession
{
    public function __construct(
        private readonly ConversationFlowService $flow,
    ) {
    }

    public function handle(ConversationSession $session): ConversationSession
    {
        $session->forceFill([
            'ground_rules_confirmed_at' => now(),
            'started_at' => now(),
            'last_activity_at' => now(),
            'current_turn_user_id' => $this->flow->nextTurnUserId($session),
            'status' => 'active',
        ])->save();

        return $session->refresh();
    }
}
