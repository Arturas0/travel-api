<?php

use App\Models\Role;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

uses(
    TestCase::class,
    RefreshDatabase::class,
)->in('Feature');

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function createUserWithToken(array $data): array
{
    test()->seed(RoleSeeder::class);

    $user = User::create([
        'email' => $data['email'],
        'password' => Hash::make($data['password']),
    ]);

    if ($data['role']) {
        $roleId = Role::where('name', $data['role'])->first()?->id;

        if ($roleId) {
            $user->roles()->attach($roleId);
        }
    }

    return [
        'user' => $user,
        'access_token' => $user->createToken('token')->plainTextToken,
    ];
}
