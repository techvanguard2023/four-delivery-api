<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ItemsSeeder extends Seeder
{
    public function run()
    {
        $items = [];

        for ($i = 1; $i <= 10; $i++) {
            $items[] = [
                'company_id' => 1,
                'name' => 'Item ' . $i,
                'description' => 'Description for item ' . $i,
                'image_url' => 'https://via.placeholder.com/150',
                'price' => mt_rand(10, 100), // Gera um preço aleatório entre 10 e 100
                'category_id' => mt_rand(1, 5), // Assumindo que você tem pelo menos 5 categorias
                'available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('items')->insert($items);
    }
}