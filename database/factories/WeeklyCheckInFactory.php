<?php

namespace Database\Factories;

use App\Domains\CheckIn\Models\WeeklyCheckIn;
use App\Domains\Couple\Models\Couple;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WeeklyCheckIn>
 */
class WeeklyCheckInFactory extends Factory
{
    protected $model = WeeklyCheckIn::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'couple_id' => Couple::factory(),
            'week_start_date' => now()->startOfWeek()->toDateString(),
            'emotional_availability' => fake()->numberBetween(1, 5),
            'communication_effort' => fake()->numberBetween(1, 5),
            'stress_level' => fake()->numberBetween(1, 5),
            'appreciation_text' => fake()->optional()->sentence(),
            'struggle_text' => fake()->optional()->sentence(),
            'intention_text' => fake()->optional()->sentence(),
        ];
    }
}
