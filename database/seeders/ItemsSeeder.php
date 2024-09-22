<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ItemsSeeder extends Seeder
{
    public function run()
    {
        Item::create([
            'company_id' => 1,
            'name' => 'Coca-Cola lata 350ml',
            'description' => 'Refri Coca-Cola lata 350ml',
            'image_url' => 'https://tdc0wy.vteximg.com.br/arquivos/ids/163808-300-300/REFRIGERANTE-COCA-COLA-LATA-350ML-ORIGINAL.png?v=638504173989400000',
            'price' => 4.50,
            'category_id' => 1,
            'available' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Item::create([
            'company_id' => 1,
            'name' => 'Fanta Laranja lata 350ml',
            'description' => 'Refri Fanta lata 350ml',
            'image_url' => 'https://images.tcdn.com.br/img/img_prod/1115696/fanta_laranja_lata_350ml_6_und_39_1_ea7725d8f660b15a6ec56f3bf0af2b2b.jpg',
            'price' => 3.50,
            'category_id' => 1,
            'available' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Item::create([
            'company_id' => 1,
            'name' => 'Guaraná Antártica lata 350ml',
            'description' => 'Refri Guaraná Antártica lata 350ml',
            'image_url' => 'https://www.imigrantesbebidas.com.br/bebida/images/products/full/1935-refrigerante-guarana-antarctica-lata-350ml.jpg',
            'price' => 3.50,
            'category_id' => 1,
            'available' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Item::create([
            'company_id' => 1,
            'name' => 'Água Mineral Cristal 500ml',
            'description' => 'Água Mineral 500ml',
            'image_url' => 'https://drogariamoderna.vtexassets.com/arquivos/ids/251997-800-auto?v=638151218022670000&width=800&height=auto&aspect=true',
            'price' => 2.50,
            'category_id' => 1,
            'available' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Item::create([
            'company_id' => 1,
            'name' => 'Cerveja Skol 350ml',
            'description' => 'Cerveja Skol 350ml',
            'image_url' => 'https://destro.fbitsstatic.net/img/p/cerveja-skol-lata-350ml-77725/264279.jpg?w=500&h=500&v=no-change&qs=ignore',
            'price' => 3.50,
            'category_id' => 1,
            'available' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
