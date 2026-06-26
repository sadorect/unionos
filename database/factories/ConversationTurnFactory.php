<?php

namespace Database\Factories;

use App\Domains\Conversation\Models\ConversationSession;
use App\Domains\Conversation\Models\ConversationTurn;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ConversationTurn>
 */
class ConversationTurnFactory extends Factory
{
    protected $model = ConversationTurn::class;

    public function definition(): array
    {
        return [
            'session_id' => ConversationSession::factory(),
            'user_id' => User::factory(),
            'prompt_key' => 'weekly-reset-1.slot_1',
            'response_text' => fake()->sentence(),
            'created_at' => now(),
        ];
    }
}
