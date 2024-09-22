<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $features = [
            [
                'name' => 'Controle de Estoque',
                'description' => 'Stock Management description',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Site de apresentação',
                'description' => 'Presentation site description',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bot de atendimento ao cliente pelo Whatsapp',
                'description' => 'Customer service bot via Whatsapp description',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Suporte 24/7',
                'description' => '24/7 support description',
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ];

        foreach ($features as $feature) {
            \App\Models\Feature::create($feature);
        }
    }
}
