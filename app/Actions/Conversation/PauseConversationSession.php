<?php

namespace App\Actions\Conversation;

use App\Events\FeatureActivityOccurred;
use App\Domains\Conversation\Models\ConversationSession;

class PauseConversationSession
{
    public function handle(ConversationSession $session): ConversationSession
    {
        $session->forceFill([
            'status' => 'paused',
            'paused_at' => now(),
            'last_activity_at' => now(),
        ])->save();

        event(new FeatureActivityOccurred('conversation_session', 'paused', [
            'status' => 'paused',
            'pack_type' => $session->pack_type,
            'pack_version' => $session->pack_version,
        ]));

        return $session->refresh();
    }
}
