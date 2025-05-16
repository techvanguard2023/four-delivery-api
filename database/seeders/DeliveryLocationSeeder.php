<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DeliveryLocation;

class DeliveryLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $deliveryLocations = [
            ['company_id' => 2, 'name' => '7 Cruzes', 'tax' => 7.00],
            ['company_id' => 2, 'name' => 'Anáia', 'tax' => 5.00],
            ['company_id' => 2, 'name' => 'Arrastão', 'tax' => 7.00],
            ['company_id' => 2, 'name' => 'Arsenal', 'tax' => 5.00],
            ['company_id' => 2, 'name' => 'Almerinda', 'tax' => 7.00],
            ['company_id' => 2, 'name' => 'Campo Novo', 'tax' => 7.00],
            ['company_id' => 2, 'name' => 'Capote', 'tax' => 7.00],
            ['company_id' => 2, 'name' => 'Coelho', 'tax' => 8.00],
            ['company_id' => 2, 'name' => 'Colubandê', 'tax' => 7.00],
            ['company_id' => 2, 'name' => 'Ipiíba', 'tax' => 7.00],
            ['company_id' => 2, 'name' => 'Jardim República', 'tax' => 5.00],
            ['company_id' => 2, 'name' => 'Joquei', 'tax' => 5.00],
            ['company_id' => 2, 'name' => 'Maria Paula', 'tax' => 8.00],
            ['company_id' => 2, 'name' => 'Nova Grécia', 'tax' => 7.00],
            ['company_id' => 2, 'name' => 'Raul Veiga', 'tax' => 8.00],
            ['company_id' => 2, 'name' => 'Rio do Ouro', 'tax' => 7.00],
            ['company_id' => 2, 'name' => 'Tribobó', 'tax' => 5.00],
        ];
        foreach ($deliveryLocations as $deliveryLocation) {
            DeliveryLocation::create($deliveryLocation);
        }
    }
}
