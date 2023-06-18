<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Travel;
use Database\Seeders\TravelSeeder;
use Symfony\Component\HttpFoundation\Response;

test('can get public travels resource correctly', function (): void {
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

test('can get paginated travels resource', function (): void {
    Travel::factory(16)->create(['is_public' => true]);

    $response = $this->get('api/v1/travels');

    $response->assertStatus(200)
        ->assertJsonCount(15, 'data')
        ->assertJsonPath('meta.last_page',2);
});

test('admin user can create new travel', function (): void {
    $email = 'admin@example.gov';
    $password = '123456';

    $userData = createUserWithToken([
        'email' => $email,
        'password' => $password,
        'role' => 'admin',
    ]);

    $travelData = [
        'is_public' => true,
        'name' => 'New travel Kaunas to New York',
        'description' => 'New travel available',
        'number_of_days' => 21,
    ];

    $response = $this
        ->withHeaders([
            'Authorization' => 'Bearer '.$userData['access_token'],
        ])
        ->post('api/v1/travels', $travelData);

    $response->assertStatus(Response::HTTP_CREATED)
        ->assertJsonStructure([
            'data' => [
                'type',
                'id',
                'attributes',
            ]
        ])
        ->assertJsonPath('data.attributes.name', $travelData['name']);
});

test('editor user cannot create new travel', function (): void {
    $email = 'editor@example.gov';
    $password = '123456';

    $userData = createUserWithToken([
        'email' => $email,
        'password' => $password,
        'role' => 'editor',
    ]);

    $travelData = [
        'is_public' => true,
        'name' => 'New travel Kaunas to New York',
        'description' => 'New travel available',
        'number_of_days' => 21,
    ];

    $response = $this
        ->withHeaders([
            'Authorization' => 'Bearer '.$userData['access_token'],
        ])
        ->post('api/v1/travels', $travelData);

    $response->assertStatus(Response::HTTP_FORBIDDEN);
});


test('editor user can update existing travel', function (): void {
    $email = 'editor@example.gov';
    $password = '123456';

    $userData = createUserWithToken([
        'email' => $email,
        'password' => $password,
        'role' => 'editor',
    ]);

    $travel = Travel::factory()->create();

    $newTravelData = [
        'is_public' => true,
        'name' => 'Travel Kaunas to New York',
        'description' => 'Travel available',
        'number_of_days' => 12,
    ];

    $response = $this
        ->withHeaders([
            'Authorization' => 'Bearer '.$userData['access_token'],
        ])
        ->post('api/v1/travels/'.$travel->slug, $newTravelData);

    $response->assertStatus(Response::HTTP_OK)
        ->assertJsonPath('data.attributes.number_of_days', $newTravelData['number_of_days']);
});
