<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::create([
            'name' => 'João da Silva',
            'phone' => '1234567890',
            'company_id' => 1,
        ]);

        Customer::create([
            'name' => 'Maria Souza',
            'phone' => '9876543210',
            'company_id' => 2,
        ]);

        Customer::create([
            'name' => 'Carlos Pereira',
            'phone' => '5555755555',
            'company_id' => 1,
        ]);

        Customer::create([
            'name' => 'Ana Costa',
            'phone' => '1111111111',
            'company_id' => 2,
        ]);

        Customer::create([
            'name' => 'Pedro Santos',
            'phone' => '2222222222',
            'company_id' => 1,
        ]);

        Customer::create([
            'name' => 'Luiza Oliveira',
            'phone' => '3333333333',
            'company_id' => 2,
        ]);

        Customer::create([
            'name' => 'Marcelo Mendes',
            'phone' => '4444444444',
            'company_id' => 1,
        ]);

        Customer::create([
            'name' => 'Juliana Ferreira',
            'phone' => '5555555555',
            'company_id' => 2,
        ]);

        Customer::create([
            'name' => 'Rafaela Gomes',
            'phone' => '6666666666',
            'company_id' => 1,
        ]);

        Customer::create([
            'name' => 'André Sousa',
            'phone' => '7777777777',
            'company_id' => 2,
        ]);

        Customer::create([
            'name' => 'Carla Ferreira',
            'phone' => '8888888888',
            'company_id' => 1,
        ]);

        Customer::create([
            'name' => 'Fernando Rodrigues',
            'phone' => '9999999999',
            'company_id' => 2,
        ]);

        Customer::create([
            'name' => 'Mariana Silva',
            'phone' => '1010101010',
            'company_id' => 1,
        ]);
    }
}
