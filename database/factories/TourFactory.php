<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class TourFactory extends Factory
{
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween(now(), now()->addDays(rand(4,21))) ;

        return [
            'travel_id' => null,
            'name' => fake()->name(),
            'start_date' => $startDate,
            'end_date' => $startDate,
            'price' => rand(30000, 120000),
        ];
    }
}
