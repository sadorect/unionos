<?php

namespace Database\Factories;

use App\Domains\Conversation\Models\ConversationSession;
use App\Domains\Couple\Models\Couple;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ConversationSession>
 */
class ConversationSessionFactory extends Factory
{
    protected $model = ConversationSession::class;

    public function definition(): array
    {
        $user = User::factory();

        return [
            'couple_id' => Couple::factory(),
            'started_by_user_id' => $user,
            'current_turn_user_id' => $user,
            'pack_type' => 'weekly-reset',
            'pack_version' => 1,
            'status' => 'active',
        ];
    }
}
