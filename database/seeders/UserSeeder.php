<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    private static array $passwords;

    public function run(): void
    {
        self::$passwords = [
            'Rm@150917' => Hash::make('Rm@150917'),
            'Ve151180@' => Hash::make('Ve151180@'),
            'Reis@132333' => Hash::make('Reis@132333'),
        ];

        $users = [
            [
                'name' => 'Administrador do Sistema',
                'email' => 'admin@techvanguard.com.br',
                'phone' => '21981321890',
                'password' => 'Rm@150917',
                'company_id' => 1,
            ],
            [
                'name' => 'Verucia Freignan',
                'email' => 'veruciafreignan@hotmail.com',
                'phone' => '2175239483',
                'password' => 'Ve151180@',
                'company_id' => 2,
            ],
            [
                'name' => 'Alessandro Reis',
                'email' => 'ale.reis1@gmail.com',
                'phone' => '21964611084',
                'password' => 'Reis@132333',
                'company_id' => 2,
            ],
            [
                'name' => 'Point do Oásis - Garçom',
                'email' => 'pointdooasisgarçom@gmail.com',
                'phone' => '21981321890',
                'password' => 'Rm@150917',
                'company_id' => 2,
            ],
            [
                'name' => 'Point do Oásis - Entregador',
                'email' => 'pointdooasisentregador@gmail.com',
                'phone' => '21981321890',
                'password' => 'Rm@150917',
                'company_id' => 2,
            ],
            [
                'name' => 'Aleksandr Calheiros',
                'email' => 'emporiodosaborgerente@gmail.com',
                'phone' => '21981321890',
                'password' => 'Rm@150917',
                'company_id' => 3,
            ],
            [
                'name' => 'Emporio do Sabor - Atendente',
                'email' => 'emporiodosaboratendente@gmail.com',
                'phone' => '21981321890',
                'password' => 'Rm@150917',
                'company_id' => 3,
            ],
            [
                'name' => 'Emporio do Sabor - Garçom',
                'email' => 'emporiodosaborgarçom@gmail.com',
                'phone' => '21981321890',
                'password' => 'Rm@150917',
                'company_id' => 3,
            ],
            [
                'name' => 'Emporio do Sabor - Entregador',
                'email' => 'emporiodosaborentregador@gmail.com',
                'phone' => '21981321890',
                'password' => 'Rm@150917',
                'company_id' => 3,
            ],
            [
                'name' => 'Bot Whatsapp',
                'email' => 'botpointdooasis@gmail.com',
                'phone' => '21981321890',
                'password' => 'Rm@150917',
                'company_id' => 2,
            ],
        ];

        foreach ($users as $user) {
            User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'phone' => $user['phone'],
                'password' => self::$passwords[$user['password']],
                'company_id' => $user['company_id'],
            ]);
        }
    }
}
