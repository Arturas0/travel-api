<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Travel;
use Database\Seeders\TravelSeeder;

test('can get paginated travels resource', function (): void {
    $this->seed(TravelSeeder::class);

    $response = $this->get('api/v1/travels');

    $response->assertStatus(200)
        ->assertJsonCount(2, 'data');

    Travel::where('is_public', true)
        ->first()
        ->update([
            'is_public' => false,
        ]);

    $response = $this->get('api/v1/travels');

    $response->assertStatus(200)
        ->assertJsonCount(1, 'data');
});
