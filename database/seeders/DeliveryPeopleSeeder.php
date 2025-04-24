<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class DeliveryPeopleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create(); // Instância do Faker
        $vehicles = ['Carro', 'Moto', 'Bicicleta'];

        $deliveryPeople = [];
        for ($i = 1; $i <= 15; $i++) {
            $deliveryPeople[] = [
                'company_id' => mt_rand(1, 2),
                'name' => $faker->name,
                'phone' => $faker->phoneNumber,
                'is_whatsapp' => $faker->boolean,
                'vehicle' => $vehicles[array_rand($vehicles)],
                'created_at' => now(),
            ];
        }

        DB::table('delivery_people')->insert($deliveryPeople);
    }
}
