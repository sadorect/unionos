<?php

namespace App\Domains\Conversation\Policies;

use App\Domains\Conversation\Models\ConversationTurn;
use App\Domains\Conversation\Services\ConversationFlowService;
use App\Models\User;

class ConversationTurnPolicy
{
    public function __construct(
        private readonly ConversationFlowService $flow,
    ) {
    }

    public function view(User $user, ConversationTurn $turn): bool
    {
        return $this->flow->belongsToUsersActiveCouple($user, $turn->session);
    }
}
