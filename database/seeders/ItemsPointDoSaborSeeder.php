<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemsPointDoSaborSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            //Bebidas Alcoolicas
            [
                'company_id' => 2,
                'name' => 'Cerveja Heineken 0,0% lata 350ml',
                'description' => 'Cerveja Heineken 0,0% álcool lata 350ml',
                'image_url' => 'https://phygital-files.mercafacil.com/catalogo/uploads/produto/cerveja_heineken_sem_lcool_lata_350ml_3f1a4b8c-5e6f-4a7b-8c9d-5e2f3a1b7c9b.jpg',
                'price' => 5.50,
                'category_id' => 21,
                'available' => true,
            ],
            [
                'company_id' => 2,
                'name' => 'Cerveja Heineken long neck 330ml',
                'description' => 'Cerveja Heineken long neck 330ml',
                'image_url' => 'https://phygital-files.mercafacil.com/catalogo/uploads/produto/cerveja_heineken_long_neck_330ml_3f1a4b8c-5e6f-4a7b-8c9d-5e2f3a1b7c9b.jpg',
                'price' => 6.50,
                'category_id' => 21,
                'available' => true,
            ],
            [
                'company_id' => 2,
                'name' => 'Cerveja Heineken lata 350ml',
                'description' => 'Cerveja Heineken lata 350ml',
                'image_url' => 'https://phygital-files.mercafacil.com/catalogo/uploads/produto/cerveja_heineken_lata_350ml_3f1a4b8c-5e6f-4a7b-8c9d-5e2f3a1b7c9b.jpg',
                'price' => 5.00,
                'category_id' => 21,
                'available' => true,
            ],
            
        ];
        
        foreach ($items as $item) {
            $timestamp = now()->timestamp; // Pega o timestamp atual
            $slug = Str::slug($item['name']) . '-' . $timestamp; // Gera o slug com timestamp

            Item::create(array_merge($item, [
                'slug' => $slug,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
