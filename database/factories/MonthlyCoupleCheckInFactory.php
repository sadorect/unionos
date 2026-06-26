<?php

namespace Database\Factories;

use App\Domains\CheckIn\Models\MonthlyCoupleCheckIn;
use App\Domains\Couple\Models\Couple;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MonthlyCoupleCheckIn>
 */
class MonthlyCoupleCheckInFactory extends Factory
{
    protected $model = MonthlyCoupleCheckIn::class;

    public function definition(): array
    {
        return [
            'couple_id' => Couple::factory(),
            'user_id' => User::factory(),
            'month' => now()->format('Y-m'),
            'felt_heard' => fake()->numberBetween(1, 5),
            'felt_appreciated' => fake()->numberBetween(1, 5),
            'conflict_safety' => fake()->numberBetween(1, 5),
            'emotional_closeness' => fake()->numberBetween(1, 5),
            'submitted_at' => now(),
        ];
    }
}
