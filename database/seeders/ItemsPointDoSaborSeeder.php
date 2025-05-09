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
                'price' => 11.00,
                'category_id' => 21,
                'available' => true,
            ],
            [
                'company_id' => 2,
                'name' => 'Cerveja Heineken long neck 330ml',
                'description' => 'Cerveja Heineken long neck 330ml',
                'image_url' => 'https://phygital-files.mercafacil.com/catalogo/uploads/produto/cerveja_heineken_long_neck_330ml_3f1a4b8c-5e6f-4a7b-8c9d-5e2f3a1b7c9b.jpg',
                'price' => 9.00,
                'category_id' => 21,
                'available' => true,
            ],
            [
                'company_id' => 2,
                'name' => 'Mini Cracudinha Brahama 300ml',
                'description' => 'Mini Cracudinha Brahama 300ml',
                'image_url' => 'https://storage.deliveryvip.com.br/dMs7GqcyExGG4bajKdQi-XdWuq-mppgISUU22IZrrvA/s:400:0/Z3M6Ly9kZWxpdmVy/eXZpcC9yNGNxcG0z/amgycmQ4dnlrdnQ3/OG96eWJvZTdo',
                'price' => 5.00,
                'category_id' => 21,
                'available' => true,
            ],
            [
                'company_id' => 2,
                'name' => 'Cerveja Império Puro Malte Lager 275ml',
                'description' => 'Cerveja Império Puro Malte Lager 275ml',
                'image_url' => 'https://carrefourbrfood.vtexassets.com/arquivos/ids/208025/5192722_1.jpg?v=637272506300370000',
                'price' => 6.00,
                'category_id' => 21,
                'available' => true,
            ],
            [
                'company_id' => 2,
                'name' => 'Cerveja Império Puro Malte Gold 210ml',
                'description' => 'Cerveja Império Puro Malte Gold 210ml',
                'image_url' => 'https://carrefourbrfood.vtexassets.com/arquivos/ids/207995/5195217_1.jpg?v=637272506276270000',
                'price' => 6.50,
                'category_id' => 21,
                'available' => true,
            ],
            [
                'company_id' => 2,
                'name' => 'Cerveja Brahma Chopp Pilsen Lata 473ml',
                'description' => 'Cerveja Brahma Chopp Pilsen Lata 473ml',
                'image_url' => 'https://www.extrabom.com.br/uploads/produtos/original/4655_extrabom_cervejas-tradicionais_cerveja-brahma-chopp-pilsen-473ml-lata.jpg',
                'price' => 8.00,
                'category_id' => 21,
                'available' => true,
            ],
            [
                'company_id' => 2,
                'name' => 'Caipirinha',
                'description' => 'Caipirinha com cachaça e limão 500ml',
                'image_url' => 'https://static.wixstatic.com/media/49591c_24539871c7ff486db0be858b5f3475e2~mv2.jpg/v1/fill/w_568,h_378,al_c,q_80,usm_0.66_1.00_0.01,enc_avif,quality_auto/49591c_24539871c7ff486db0be858b5f3475e2~mv2.jpg',
                'price' => 12.00,
                'category_id' => 21,
                'available' => true,
            ],
            [
                'company_id' => 2,
                'name' => 'Caneca de vinho',
                'description' => 'Caneca de vinho de 500ml',
                'image_url' => 'https://euamovinhos.com.br/wp-content/uploads/2021/11/image-85.png',
                'price' => 12.00,
                'category_id' => 21,
                'available' => true,
            ],
            [
                'company_id' => 2,
                'name' => 'Garrafa chopp vinho',
                'description' => 'Garrafa chopp vinho 600ml',
                'image_url' => 'https://zonasul.vtexassets.com/arquivos/ids/3088058/VF4qT-qqCUAAAAAAAAPGDA.jpg?v=638076741337130000',
                'price' => 12.00,
                'category_id' => 21,
                'available' => true,
            ],
            [
                'company_id' => 2,
                'name' => 'Chopp Zero Grau',
                'description' => 'Caneca de chopp zero grau 500ml',
                'image_url' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT12hZ8j5I52LL7pbM5_udVWoCzMSeLgZsOpg&s',
                'price' => 7.00,
                'category_id' => 21,
                'available' => true,
            ],

            //Bebidas Frias NÃO Alcoolicas

            [
                'company_id' => 2,
                'name' => 'Água SEM Gás',
                'description' => 'Água Crystal Sem Gás 500ml',
                'image_url' => 'https://andinacocacola.vtexassets.com/arquivos/ids/157883/113325_COCA---Crystal500ml-SemGas.jpg?v=638412015595530000',
                'price' => 3.00,
                'category_id' => 1,
                'available' => true,
            ],
            [
                'company_id' => 2,
                'name' => 'Água COM Gás',
                'description' => 'Água Mineral com Gás Crystal 500 ml',
                'image_url' => 'https://images.tcdn.com.br/img/img_prod/858764/agua_crystal_500ml_gas_c_12_127_1_a126e0b536ba991756801f870daaff60.jpg',
                'price' => 4.00,
                'category_id' => 1,
                'available' => true,
            ],
            [
                'company_id' => 2,
                'name' => 'Guaravita',
                'description' => 'Guaravita 290ml',
                'image_url' => 'https://biruli.com.br/cdn/shop/products/guaravita-suco-sabor-original-290ml-delivery-de-bebidas-em-cabo-frio.jpg?v=1662633400',
                'price' => 3.00,
                'category_id' => 1,
                'available' => true,
            ],
            [
                'company_id' => 2,
                'name' => 'Guaraviton',
                'description' => 'Guaraviton Ginseng 500ml',
                'image_url' => 'https://a-static.mlcdn.com.br/800x560/guaraviton-ginseng-500ml/drogariaaraujosa/249777/c3760d923e3b11298107357694fc82d0.jpeg',
                'price' => 6.00,
                'category_id' => 1,
                'available' => true,
            ],
            [
                'company_id' => 2,
                'name' => 'Água Tônica',
                'description' => 'Água Tônica Antarctica Lata 350 Ml',
                'image_url' => 'https://mercantilnovaera.vtexassets.com/arquivos/ids/217345-800-450?v=638539045346730000&width=800&height=450&aspect=true',
                'price' => 8.00,
                'category_id' => 1,
                'available' => true,
            ],
            [
                'company_id' => 2,
                'name' => '',
                'description' => '',
                'image_url' => '',
                'price' => 7.00,
                'category_id' => 1,
                'available' => true,
            ],
            [
                'company_id' => 2,
                'name' => '',
                'description' => '',
                'image_url' => '',
                'price' => 7.00,
                'category_id' => 1,
                'available' => true,
            ],
            [
                'company_id' => 2,
                'name' => '',
                'description' => '',
                'image_url' => '',
                'price' => 7.00,
                'category_id' => 1,
                'available' => true,
            ],
            [
                'company_id' => 2,
                'name' => '',
                'description' => '',
                'image_url' => '',
                'price' => 7.00,
                'category_id' => 1,
                'available' => true,
            ],
            [
                'company_id' => 2,
                'name' => '',
                'description' => '',
                'image_url' => '',
                'price' => 7.00,
                'category_id' => 1,
                'available' => true,
            ],
            [
                'company_id' => 2,
                'name' => '',
                'description' => '',
                'image_url' => '',
                'price' => 7.00,
                'category_id' => 1,
                'available' => true,
            ],
            [
                'company_id' => 2,
                'name' => '',
                'description' => '',
                'image_url' => '',
                'price' => 7.00,
                'category_id' => 1,
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
