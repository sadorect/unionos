<?php

namespace Database\Factories;

use App\Domains\Couple\Enums\CoupleStatus;
use App\Domains\Couple\Models\Couple;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Couple>
 */
class CoupleFactory extends Factory
{
    protected $model = Couple::class;

    public function definition(): array
    {
        return [
            'status' => CoupleStatus::Pending,
        ];
    }
}
