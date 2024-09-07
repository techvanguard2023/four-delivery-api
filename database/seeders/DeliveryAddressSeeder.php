<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DeliveryAddress;

class DeliveryAddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DeliveryAddress::create([
            'customer_id' => 1,
            'address' => 'Rua das Flores',
            'number' => '123',
            'complement' => 'apto 101',
            'neighborhood' => 'Centro',
            'reference_point' => 'Próximo ao posto de gasolina',
            'city' => 'São Paulo',
            'state' => 'SP',
            'zip_code' => '01310100',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DeliveryAddress::create([
            'customer_id' => 2,
            'address' => 'Avenida dos Anjos, 456',
            'number' => '456',
            'complement' => 'apto 202',
            'neighborhood' => 'Copacabana',
            'reference_point' => 'Próximo ao shopping',
            'city' => 'Rio de Janeiro',
            'state' => 'RJ',
            'zip_code' => '20090000',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DeliveryAddress::create([
            'customer_id' => 3,
            'address' => 'Rua das Palmeiras, 789',
            'number' => '789',
            'complement' => 'apto 303',
            'neighborhood' => 'Savassi',
            'reference_point' => 'Próximo ao hospital',
            'city' => 'Belo Horizonte',
            'state' => 'MG',
            'zip_code' => '30170000',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DeliveryAddress::create([
            'customer_id' => 4,
            'address' => 'Rua das Flores, 123',
            'number' => '123',
            'complement' => 'apto 101',
            'neighborhood' => 'Centro',
            'reference_point' => 'Próximo ao posto de gasolina',
            'city' => 'São Paulo',
            'state' => 'SP',
            'zip_code' => '01310100',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DeliveryAddress::create([
            'customer_id' => 5,
            'address' => 'Rua das Flores, 123',
            'number' => '123',
            'complement' => 'apto 101',
            'neighborhood' => 'Centro',
            'reference_point' => 'Próximo ao posto de gasolina',
            'city' => 'São Paulo',
            'state' => 'SP',
            'zip_code' => '01310100',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DeliveryAddress::create([
            'customer_id' => 6,
            'address' => 'Rua das Flores, 123',
            'number' => '123',
            'complement' => 'apto 101',
            'neighborhood' => 'Centro',
            'reference_point' => 'Próximo ao posto de gasolina',
            'city' => 'São Paulo',
            'state' => 'SP',
            'zip_code' => '01310100',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DeliveryAddress::create([
            'customer_id' => 7,
            'address' => 'Rua das Flores, 123',
            'number' => '123',
            'complement' => 'apto 101',
            'neighborhood' => 'Centro',
            'reference_point' => 'Próximo ao posto de gasolina',
            'city' => 'São Paulo',
            'state' => 'SP',
            'zip_code' => '01310100',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DeliveryAddress::create([
            'customer_id' => 8,
            'address' => 'Rua das Flores, 123',
            'number' => '123',
            'complement' => 'apto 101',
            'neighborhood' => 'Centro',
            'reference_point' => 'Próximo ao posto de gasolina',
            'city' => 'São Paulo',
            'state' => 'SP',
            'zip_code' => '01310100',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DeliveryAddress::create([
            'customer_id' => 9,
            'address' => 'Rua das Flores, 123',
            'number' => '123',
            'complement' => 'apto 101',
            'neighborhood' => 'Centro',
            'reference_point' => 'Próximo ao posto de gasolina',
            'city' => 'São Paulo',
            'state' => 'SP',
            'zip_code' => '01310100',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DeliveryAddress::create([
            'customer_id' => 10,
            'address' => 'Rua das Flores, 123',
            'number' => '123',
            'complement' => 'apto 101',
            'neighborhood' => 'Centro',
            'reference_point' => 'Próximo ao posto de gasolina',
            'city' => 'São Paulo',
            'state' => 'SP',
            'zip_code' => '01310100',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
