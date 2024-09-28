<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    protected static ?string $password;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Robson Gomes Pedreira',
            'email' => 'masterdba6@gmail.com',
            'phone' => '21981321890',
            'password' => static::$password ??= Hash::make('Rm@150917'),
            'company_id' => 1
        ]);

        User::create([
            'name' => 'Gerente',
            'email' => 'gerente@gmail.com',
            'phone' => '21981321890',
            'password' => static::$password ??= Hash::make('Rm@150917'),
            'company_id' => 1
        ]);

        User::create([
            'name' => 'Atendente',
            'email' => 'atendente@gmail.com',
            'phone' => '21981321890',
            'password' => static::$password ??= Hash::make('Rm@150917'),
            'company_id' => 1
        ]);

        User::create([
            'name' => 'Emporio dos Sabores Gerente',
            'email' => 'emporiodosaborgerente@gmail.com',
            'phone' => '21981321890',
            'password' => static::$password ??= Hash::make('Rm@150917'),
            'company_id' => 2
        ]);

        User::create([
            'name' => 'Emporio dos Sabores Atendente',
            'email' => 'emporiodosaboratendente@gmail.com',
            'phone' => '21981321890',
            'password' => static::$password ??= Hash::make('Rm@150917'),
            'company_id' => 2
        ]);
    }
}