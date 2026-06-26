<?php

namespace App\Domains\Conversation\Enums;

enum ConversationSessionStatus: string
{
    case Active = 'active';
    case Paused = 'paused';
    case Completed = 'completed';
    case Abandoned = 'abandoned';
}
