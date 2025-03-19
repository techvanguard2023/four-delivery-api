<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    private static $password;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Administrador do Sistema',
            'email' => 'admin@techvanguard.com.br',
            'phone' => '21981321890',
            'password' => static::$password ??= Hash::make('Rm@150917'),
            'company_id' => 1
        ]);



        User::create([
            'name' => 'Aleksander Calheiros',
            'email' => 'emporiodosaborgerente@gmail.com',
            'phone' => '21981321890',
            'password' => static::$password ??= Hash::make('Rm@150917'),
            'company_id' => 2
        ]);

        User::create([
            'name' => 'Emporio do Sabor - Atendente',
            'email' => 'emporiodosaboratendente@gmail.com',
            'phone' => '21981321890',
            'password' => static::$password ??= Hash::make('Rm@150917'),
            'company_id' => 2
        ]);

        User::create([
            'name' => 'Emporio do Sabor - Garçom',
            'email' => 'emporiodosaborgarçom@gmail.com',
            'phone' => '21981321890',
            'password' => static::$password ??= Hash::make('Rm@150917'),
            'company_id' => 2
        ]);

        User::create([
            'name' => 'Emporio do Sabor - Entregador',
            'email' => 'emporiodosaborentregador@gmail.com',
            'phone' => '21981321890',
            'password' => static::$password ??= Hash::make('Rm@150917'),
            'company_id' => 2
        ]);



        User::create([
            'name' => 'Verucia Freignan',
            'email' => 'pointdooasisgerente@gmail.com',
            'phone' => '21981321890',
            'password' => static::$password ??= Hash::make('Rm@150917'),
            'company_id' => 3
        ]);

        User::create([
            'name' => 'Point do Oásis - Atendente',
            'email' => 'pointdooasisatendente@gmail.com',
            'phone' => '21981321890',
            'password' => static::$password ??= Hash::make('Rm@150917'),
            'company_id' => 3
        ]);

        User::create([
            'name' => 'Point do Oásis - Garçom',
            'email' => 'pointdooasisgarçom@gmail.com',
            'phone' => '21981321890',
            'password' => static::$password ??= Hash::make('Rm@150917'),
            'company_id' => 3
        ]);

        User::create([
            'name' => 'Point do Oásis - Entregador',
            'email' => 'pointdooasisentregador@gmail.com',
            'phone' => '21981321890',
            'password' => static::$password ??= Hash::make('Rm@150917'),
            'company_id' => 3
        ]);
    }
}
