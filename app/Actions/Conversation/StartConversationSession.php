<?php

namespace App\Actions\Conversation;

use App\Events\FeatureActivityOccurred;
use App\Domains\Conversation\Models\ConversationSession;
use App\Domains\Conversation\Services\ConversationPackService;
use App\Domains\Couple\Models\Couple;
use App\Models\User;

class StartConversationSession
{
    public function __construct(
        private readonly ConversationPackService $packs,
    ) {
    }

    public function handle(User $user, Couple $couple, string $packType): ConversationSession
    {
        $pack = $this->packs->active($packType);

        abort_if($pack === null, 404);

        $session = ConversationSession::query()->create([
            'couple_id' => $couple->id,
            'started_by_user_id' => $user->id,
            'pack_type' => $packType,
            'pack_version' => $pack->version,
            'status' => 'active',
        ]);

        event(new FeatureActivityOccurred('conversation_session', 'started', [
            'status' => 'started',
            'pack_type' => $packType,
            'pack_version' => $pack->version,
        ]));

        return $session;
    }
}
