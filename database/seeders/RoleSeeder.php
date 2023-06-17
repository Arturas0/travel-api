<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Uid\Factory\UlidFactory;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'id' => (new UlidFactory)->create(now()),
                'name' => 'admin',
                'created_at' => now(),
            ],
            [
                'id' => (new UlidFactory)->create(now()),
                'name' => 'editor',
                'created_at' => now(),
            ],
        ];

        DB::table('roles')->insert($data);
    }
}
