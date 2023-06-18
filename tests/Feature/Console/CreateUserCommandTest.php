<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Database\Seeders\RoleSeeder;

test('can create user using artisan command', function (): void {
    $this->seed(RoleSeeder::class);

    $this->artisan('users:create')
        ->expectsQuestion('What is user email?', 'test@test.com')
        ->expectsChoice('What is user role', 'admin', [
            0 => 'admin',
            1 => 'editor',
        ])
        ->expectsQuestion('What is the user password? (min 6 symbols)', '123456')
        ->expectsOutput('User with email: test@test.com was created.')
        ->assertExitCode(0);

    $userId = User::query()->latest('id')->first()->id;
    $roleId = Role::query()->first()->id;

    $this->assertDatabaseHas('role_user', [
        'user_id' => $userId,
        'role_id' => $roleId,
    ]);
});

test('Throws validation errors, then trying register user with incorrect email', function (): void {
    $this->seed(RoleSeeder::class);

    $this->artisan('users:create')
        ->expectsQuestion('What is user email?', 'test.com')
        ->expectsChoice('What is user role', 'editor', [
            0 => 'admin',
            1 => 'editor',
        ])
        ->expectsQuestion('What is the user password? (min 6 symbols)', '123456')
        ->expectsOutput('User not created. See error messages below:')
        ->expectsOutput('The email field must be a valid email address.')
        ->assertExitCode(1);
});
