<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateUserCommand extends Command
{
    protected $signature = 'app:create-user';

    protected $description = 'Command description';

    public function handle(): int
    {
        $this->info('User registration form. Please fill all fields.').PHP_EOL;

        $roles = $this->choice(
            question: 'What is user role',
            choices: ['admin', 'editor'],
            default: 0,
            multiple: true,
        );
        $email = $this->ask('What is user email?');
        $password = $this->secret('What is the user password? (min 6 symbols)');

        if (! $this->validateUserInput($email, $password)) {
            return 1;
        };

        DB::transaction(function () use ($email, $password, $roles) {
            $user = User::create([
                'email' => $email,
                'password' => Hash::make($password)
            ]);

            $roleIds = Role::whereIn('name', Arr::wrap($roles))
                ->pluck('id', 'name')->toArray();

            $roleIds
                ?  $user->roles()->attach($roleIds)
                : $this->error('admin or editor roles are not created! Create them first and try again.');
        });

        $this->info('User with email: '.$email. ' was created.');

        return 0;
    }

    private function validateUserInput(string|null $email, string|null $password): bool
    {
        $validator = Validator::make([
            'email' => $email,
            'password' => $password,
        ], [
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:6'],
        ]);

        if ($validator->fails()) {
            $this->info('User not created. See error messages below:');

            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return false;
        }

        return true;
    }
}
