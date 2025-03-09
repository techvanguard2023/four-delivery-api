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



        //Emporio do Sabor - gerente
        UserRole::create([
            'user_id' => 2,
            'role_id' => 2,
            'created_at' => now()
        ]);

        //Emporio do Sabor - atendente
        UserRole::create([
            'user_id' => 3,
            'role_id' => 3,
            'created_at' => now()
        ]);

        //Emporio do Sabor - garçom
        UserRole::create([
            'user_id' => 4,
            'role_id' => 4,
            'created_at' => now()
        ]);

        //Emporio do Sabor - entregador
        UserRole::create([
            'user_id' => 5,
            'role_id' => 5,
            'created_at' => now()
        ]);



        //Point do Oásis -  gerente
        UserRole::create([
            'user_id' => 6,
            'role_id' => 2,
            'created_at' => now()
        ]);

        //Point do Oásis -  atendente
        UserRole::create([
            'user_id' => 7,
            'role_id' => 3,
            'created_at' => now()
        ]);

        //Point do Oásis -  garçom
        UserRole::create([
            'user_id' => 8,
            'role_id' => 4,
            'created_at' => now()
        ]);

        //Point do Oásis -  entregador
        UserRole::create([
            'user_id' => 9,
            'role_id' => 5,
            'created_at' => now()
        ]);
    }
}
