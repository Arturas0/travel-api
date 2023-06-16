<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Uid\Factory\UlidFactory;

class TravelSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'id' => (new UlidFactory)->create(now()),
                'is_public' => true,
                'name' => fake()->name(),
                'slug' => fake()->slug(),
                'description' => fake()->realText(),
                'number_of_days' => rand(4,21),
                'created_at' => now(),
            ],
            [
                'id' => (new UlidFactory)->create(now()),
                'is_public' => true,
                'name' => fake()->name(),
                'slug' => fake()->slug(),
                'description' => fake()->realText(),
                'number_of_days' => rand(4,21),
                'created_at' => now(),
            ],
            [
                'id' => (new UlidFactory)->create(now()),
                'is_public' => false,
                'name' => fake()->name(),
                'slug' => fake()->slug(),
                'description' => fake()->realText(),
                'number_of_days' => rand(4,21),
                'created_at' => now(),
            ],
        ];

        DB::table('travels')->insert($data);
    }
}
