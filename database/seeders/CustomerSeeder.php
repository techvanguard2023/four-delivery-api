<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    private static string $password;

    public function run(): void
    {
        self::$password = Hash::make('Rm@150917');

        $customers = [
            ['name' => 'João da Silva', 'phone' => '1234567890', 'email' => 'joao@gmail.com', 'company_id' => 1],
            ['name' => 'Maria Souza', 'phone' => '9876543210', 'email' => 'maria@gmail.com', 'company_id' => 2],
            ['name' => 'Carlos Pereira', 'phone' => '5555755555', 'email' => 'carlos@gmail.com', 'company_id' => 1],
            ['name' => 'Ana Costa', 'phone' => '1111111111', 'email' => 'ana@gmail.com', 'company_id' => 2],
            ['name' => 'Pedro Santos', 'phone' => '2222222222', 'email' => 'pedro@gmail.com', 'company_id' => 1],
            ['name' => 'Luiza Oliveira', 'phone' => '3333333333', 'email' => 'luiza@gmail.com', 'company_id' => 2],
            ['name' => 'Marcelo Mendes', 'phone' => '4444444444', 'email' => 'marcelo@gmail.com', 'company_id' => 1],
            ['name' => 'Juliana Ferreira', 'phone' => '5555555555', 'email' => 'juliana@gmail.com', 'company_id' => 2],
            ['name' => 'Rafaela Gomes', 'phone' => '6666666666', 'email' => 'rafaela@gmail.com', 'company_id' => 1],
            ['name' => 'André Sousa', 'phone' => '7777777777', 'email' => 'andre@gmail.com', 'company_id' => 2],
            ['name' => 'Carla Ferreira', 'phone' => '8888888888', 'email' => 'carla@gmail.com', 'company_id' => 1],
            ['name' => 'Fernando Rodrigues', 'phone' => '9999999999', 'email' => 'fernando@gmail.com', 'company_id' => 2],
            ['name' => 'Mariana Silva', 'phone' => '1010101010', 'email' => 'mariana@gmail.com', 'company_id' => 1],
        ];

        foreach ($customers as $data) {
            Customer::create([
                ...$data,
                'password' => self::$password,
                'is_whatsapp' => true,
            ]);
        }
    }
}
