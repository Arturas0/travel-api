<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

test('user can login with correct credentials', function (): void {
    $email = 'admin@example.gov';
    $password = '123456';

    User::create([
        'email' => $email,
        'password' => Hash::make($password),
    ]);

    $response = $this->post(route('v1.login', [
        'email' => $email,
        'password' => $password,
    ]));

    $response->assertStatus(Response::HTTP_OK)
        ->assertJsonStructure([
            'token',
        ]);
});

test('user cannot login with incorrect credentials', function (): void {
    $email = 'admin@example.gov';
    $password = '123456';

    User::create([
        'email' => $email,
        'password' => Hash::make($password),
    ]);

    $response = $this->withHeader('Accept', 'application/vnd.api+json')
        ->post(route('v1.login', [
                'email' => $email,
                'password' => 'incorrect-password',
            ])
        );

    $response->assertStatus(Response::HTTP_UNAUTHORIZED)
        ->assertJsonStructure([
            'message',
            'errors',
        ]);
});
