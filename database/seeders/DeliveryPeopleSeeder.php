<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DeliveryPerson;
use Illuminate\Support\Facades\DB;

class DeliveryPeopleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vehicles = ['Carro', 'Moto', 'Bicicleta'];

        $deliveryPeople = [];
        for ($i = 1; $i <= 15; $i++) {
            $deliveryPeople[] = [
                'company_id' => mt_rand(1, 2),
                'name' => fake()->name,
                'phone' => fake()->phoneNumber,
                'vehicle' => $vehicles[array_rand($vehicles)],
                'created_at' => now(),
            ];
        }

        DB::table('delivery_people')->insert($deliveryPeople);
    }
}
