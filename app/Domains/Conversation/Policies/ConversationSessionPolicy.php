<?php

namespace App\Domains\Conversation\Policies;

use App\Domains\Conversation\Enums\ConversationSessionStatus;
use App\Domains\Conversation\Models\ConversationSession;
use App\Domains\Conversation\Services\ConversationFlowService;
use App\Models\User;

class ConversationSessionPolicy
{
    public function __construct(
        private readonly ConversationFlowService $flow,
    ) {
    }

    public function viewAny(User $user): bool
    {
        return $this->flow->activeCoupleFor($user) !== null;
    }

    public function create(User $user): bool
    {
        return $this->viewAny($user);
    }

    public function view(User $user, ConversationSession $session): bool
    {
        return $this->flow->belongsToUsersActiveCouple($user, $session);
    }

    public function update(User $user, ConversationSession $session): bool
    {
        return $this->view($user, $session)
            && $session->status !== ConversationSessionStatus::Completed
            && $session->status !== ConversationSessionStatus::Abandoned;
    }
}
