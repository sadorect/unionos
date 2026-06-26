<?php

namespace App\Actions\Conversation;

use App\Domains\Conversation\Models\ConversationSession;

class ResumeConversationSession
{
    public function handle(ConversationSession $session): ConversationSession
    {
        $session->forceFill([
            'status' => 'active',
            'paused_at' => null,
            'last_activity_at' => now(),
        ])->save();

        return $session->refresh();
    }
}
