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
            'email' => 'joao.silva@example.com',
            'phone' => '1234567890',
            'address' => 'Rua das Flores, 123',
            'company_id' => 1,
        ]);

        Customer::create([
            'name' => 'Maria Souza',
            'email' => 'maria.souza@example.com',
            'phone' => '9876543210',
            'address' => 'Avenida dos Anjos, 456',
            'company_id' => 2,
        ]);

        Customer::create([
            'name' => 'Carlos Pereira',
            'email' => 'carlos.pereira@example.com',
            'phone' => '5555555555',
            'address' => 'Rua das Palmeiras, 789',
            'company_id' => 1,
        ]);

        Customer::create([
            'name' => 'Ana Costa',
            'email' => 'ana.costa@example.com',
            'phone' => '1111111111',
            'address' => 'Rua das Flores, 123',
            'company_id' => 2,
        ]);

        Customer::create([
            'name' => 'Pedro Santos',
            'email' => 'pedro.santos@example.com',
            'phone' => '2222222222',
            'address' => 'Rua das Flores, 123',
            'company_id' => 1,
        ]);

        Customer::create([
            'name' => 'Luiza Oliveira',
            'email' => 'luiza.oliveira@example.com',
            'phone' => '3333333333',
            'address' => 'Rua das Flores, 123',
            'company_id' => 2,
        ]);

        Customer::create([
            'name' => 'Marcelo Mendes',
            'email' => 'marcelo.mendes@example.com',
            'phone' => '4444444444',
            'address' => 'Rua das Flores, 123',
            'company_id' => 1,
        ]);

        Customer::create([
            'name' => 'Juliana Ferreira',
            'email' => 'juliana.ferreira@example.com',
            'phone' => '5555555555',
            'address' => 'Rua das Flores, 123',
            'company_id' => 2,
        ]);

        Customer::create([
            'name' => 'Rafaela Gomes',
            'email' => 'rafaela.gomes@example.com',
            'phone' => '6666666666',
            'address' => 'Rua das Flores, 123',
            'company_id' => 1,
        ]);

        Customer::create([
            'name' => 'André Sousa',
            'email' => 'andre.sousa@example.com',
            'phone' => '7777777777',
            'address' => 'Rua das Flores, 123',
            'company_id' => 2,
        ]);

        Customer::create([
            'name' => 'Carla Ferreira',
            'email' => 'carla.ferreira@example.com',
            'phone' => '8888888888',
            'address' => 'Rua das Flores, 123',
            'company_id' => 1,
        ]);

        Customer::create([
            'name' => 'Fernando Rodrigues',
            'email' => 'fernando.rodrigues@example.com',
            'phone' => '9999999999',
            'address' => 'Rua das Flores, 123',
            'company_id' => 2,
        ]);
    }
}
