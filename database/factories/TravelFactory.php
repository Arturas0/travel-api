<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Travel>
 */
class TravelFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'is_public' => fake()->boolean(),
            'description' => fake()->text(50),
            'number_of_days' => rand(7, 21),
        ];
    }
}
