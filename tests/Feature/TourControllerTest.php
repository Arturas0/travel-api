<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Tour;
use App\Models\Travel;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\Response;

test('can get paginated tours resource by travel slug without filters', function (): void {
    Travel::factory(3)->create(['is_public' => true]);

    $travelFirst = Travel::query()->first();

    Tour::factory(1)->create([
        'travel_id' => $travelFirst->id,
        'start_date' => now(),
        'end_date' => Carbon::createFromDate()->addDays(3),
        'price' => 200,
    ]);

    $travelLatest = Travel::query()->latest('id')->first();

    $tour1 = Tour::factory(1)->create([
        'travel_id' => $travelLatest->id,
        'start_date' => now(),
        'end_date' => Carbon::createFromDate()->addDays(4),
        'price' => 400,
    ]);

    $tour2 = Tour::factory(1)->create([
        'travel_id' => $travelLatest->id,
        'start_date' => Carbon::createFromDate()->addDays(7),
        'end_date' => Carbon::createFromDate()->addDays(17),
        'price' => 700,
    ]);

    $tour3 = Tour::factory(1)->create([
        'travel_id' => $travelLatest->id,
        'start_date' => Carbon::createFromDate()->addDay(),
        'end_date' => Carbon::createFromDate()->addDays(5),
        'price' => 500,
    ]);

    $response = $this->get(route('v1.travels.tours', [
        'travel' => $travelLatest,
    ]));

    $response->assertStatus(200)
        ->assertJsonCount(3, 'data')
        ->assertJsonPath('data.0.id', $tour1->first()->id)
        ->assertJsonPath('data.1.id', $tour3->first()->id)
        ->assertJsonPath('data.2.id', $tour2->first()->id);
});

test('can get paginated tours resource by travel slug with price filters', function (): void {
    Travel::factory(3)->create(['is_public' => true]);
    $travelLatest = Travel::query()->latest('id')->first();

    $tour1 = Tour::factory(1)->create([
        'travel_id' => $travelLatest->id,
        'start_date' => now(),
        'end_date' => Carbon::createFromDate()->addDays(4),
        'price' => 400,
    ]);

    $tour2 = Tour::factory(1)->create([
        'travel_id' => $travelLatest->id,
        'start_date' => Carbon::createFromDate()->addDays(7),
        'end_date' => Carbon::createFromDate()->addDays(17),
        'price' => 700,
    ]);

    $tour3 = Tour::factory(1)->create([
        'travel_id' => $travelLatest->id,
        'start_date' => Carbon::createFromDate()->addDay(),
        'end_date' => Carbon::createFromDate()->addDays(5),
        'price' => 500,
    ]);

    $response = $this->get(route('v1.travels.tours', [
        'travel' => $travelLatest,
        'priceFrom' => 500,
    ]));

    $response->assertStatus(200)
        ->assertJsonCount(2, 'data');

    $response = $this->get(route('v1.travels.tours', [
        'travel' => $travelLatest,
        'priceTo' => 450,
    ]));

    $response->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.id', $tour1->first()->id);

    $response = $this->get(route('v1.travels.tours', [
        'travel' => $travelLatest,
        'priceFrom' => 500,
        'priceTo' => 600,
    ]));

    $response->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.id', $tour3->first()->id);
});

test('can get paginated tours resource by travel slug with date filters and sort', function (): void {
    Travel::factory(3)->create(['is_public' => true]);
    $travelLatest = Travel::query()->latest('id')->first();

    $tour1 = Tour::factory(1)->create([
        'travel_id' => $travelLatest->id,
        'start_date' => now(),
        'end_date' => Carbon::createFromDate()->addDays(4),
        'price' => 400,
    ]);

    $tour2 = Tour::factory(1)->create([
        'travel_id' => $travelLatest->id,
        'start_date' => Carbon::createFromDate()->addDays(6),
        'end_date' => Carbon::createFromDate()->addDays(17),
        'price' => 700,
    ]);

    $tour3 = Tour::factory(1)->create([
        'travel_id' => $travelLatest->id,
        'start_date' => Carbon::createFromDate()->addDay(),
        'end_date' => Carbon::createFromDate()->addDays(5),
        'price' => 500,
    ]);

    $response = $this->get(route('v1.travels.tours', [
        'travel' => $travelLatest,
        'dateFrom' => Carbon::createFromDate()->addDays(2)->format('Y-m-d'),
    ]));

    $response->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.id', $tour2->first()->id);

    $response = $this->get(route('v1.travels.tours', [
        'travel' => $travelLatest,
        'dateFrom' => Carbon::createFromDate()->addDays()->format('Y-m-d'),
        'dateTo' => Carbon::createFromDate()->addDays(8)->format('Y-m-d'),
        'sortByPrice' => 'DESC',
    ]));

    $response
        ->assertJsonCount(2, 'data')
        ->assertJsonPath('data.0.attributes.price', $tour2->first()->price)
        ->assertJsonPath('data.1.attributes.price', $tour3->first()->price);

    $response = $this->get(route('v1.travels.tours', [
        'travel' => $travelLatest,
        'dateFrom' => Carbon::createFromDate()->addDays()->format('Y-m-d'),
        'dateTo' => Carbon::createFromDate()->addDays(8)->format('Y-m-d'),
        'sortByPrice' => 'ASC',
    ]));

    $response
        ->assertJsonCount(2, 'data')
        ->assertJsonPath('data.0.attributes.price', $tour3->first()->price)
        ->assertJsonPath('data.1.attributes.price', $tour2->first()->price);
});

test('Tours EP return 422 error code, when given invalid parameters', function (): void {
    Travel::factory(3)->create(['is_public' => true]);
    $travelLatest = Travel::query()->latest('id')->first();

    $tour1 = Tour::factory(1)->create([
        'travel_id' => $travelLatest->id,
        'start_date' => now(),
        'end_date' => Carbon::createFromDate()->addDays(4),
        'price' => 400,
    ]);

    $tour2 = Tour::factory(1)->create([
        'travel_id' => $travelLatest->id,
        'start_date' => Carbon::createFromDate()->addDays(6),
        'end_date' => Carbon::createFromDate()->addDays(17),
        'price' => 700,
    ]);

    $tour3 = Tour::factory(1)->create([
        'travel_id' => $travelLatest->id,
        'start_date' => Carbon::createFromDate()->addDay(),
        'end_date' => Carbon::createFromDate()->addDays(5),
        'price' => 500,
    ]);

    $response = $this->get(route('v1.travels.tours', [
        'travel' => $travelLatest,
        'sortByPrice' => 'random',
    ]));

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonMissing(['data'])
        ->assertJsonCount(1, 'errors');
});

test('admin user can create new tour', function (): void {
    $email = 'admin@example.gov';
    $password = '123456';

    $userData = createUserWithToken([
        'email' => $email,
        'password' => $password,
        'role' => 'admin',
    ]);

    $travel = Travel::factory()->create();

    $tourData = [
        'name' => 'Kelione 1',
        'start_date' => now(),
        'end_date' => now(),
        'price' => 300,
    ];

    $response = $this
        ->withHeaders([
            'Accept' => 'application/vnd.api+json',
            'Authorization' => 'Bearer '.$userData['access_token'],
        ])
        ->post('api/v1/travels/'.$travel->slug.'/tours', $tourData);

    $response->assertStatus(Response::HTTP_CREATED)
        ->assertJsonStructure([
            'data' => [
                'type',
                'id',
                'attributes',
            ],
        ])
        ->assertJsonPath('data.attributes.name', $tourData['name']);
});

test('user cannot create new tour with invalid data', function (): void {
    $email = 'admin@example.gov';
    $password = '123456';

    $userData = createUserWithToken([
        'email' => $email,
        'password' => $password,
        'role' => 'admin',
    ]);

    $travel = Travel::factory()->create();

    $invalidDate = now()->subDay();
    $invalidPrice = -10;

    $tourData = [
        'name' => 'Kelione 1',
        'start_date' => $invalidDate,
        'end_date' => now(),
        'price' => $invalidPrice,
    ];

    $response = $this
        ->withHeaders([
            'Accept' => 'application/vnd.api+json',
            'Authorization' => 'Bearer '.$userData['access_token'],
        ])
        ->post('api/v1/travels/'.$travel->slug.'/tours', $tourData);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonFragment([
            'message' => 'The start date field must be a date after or equal to today. (and 1 more error)',
        ]);
});
