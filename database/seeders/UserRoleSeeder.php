<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UserRole;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Robson Gomes Pedreira ADMIN
        UserRole::create([
            'user_id' => 1,
            'role_id' => 1,
            'created_at' => now()
        ]);

        //gerente
        UserRole::create([
            'user_id' => 2,
            'role_id' => 2,
            'created_at' => now()
        ]);

        //atendente
        UserRole::create([
            'user_id' => 3,
            'role_id' => 3,
            'created_at' => now()
        ]);

        //emporio dos sabores gerente
        UserRole::create([
            'user_id' => 4,
            'role_id' => 2,
            'created_at' => now()
        ]);

        //emporio dos sabores atendente
        UserRole::create([
            'user_id' => 5,
            'role_id' => 3,
            'created_at' => now()
        ]);
    }
}
