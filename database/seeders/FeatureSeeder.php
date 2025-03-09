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
            //Essencial plan features
            [
                'name' => 'Dashboard Financeiro',
                'description' => 'financial dashboard',
                'slug' => 'financial-dashboard',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Menu Digital',
                'description' => 'Digital menu',
                'slug' => 'digital menu',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cadastro de Clientes',
                'description' => 'Customer registration',
                'slug' => 'customer-registration',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Gerenciar pedidos delivery',
                'description' => 'Manage delivery orders',
                'slug' => 'manage-delivery-orders',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            //Professional plan features
            [
                'name' => 'Gerenciar pedidos pelo Whatsapp',
                'description' => 'Manage Whatsapp orders',
                'slug' => 'manage-whatsapp-orders',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Gerenciar pedidos no local',
                'description' => 'Manage local orders',
                'slug' => 'manage-local-orders',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Gerenciar Comanda Digital',
                'description' => 'Manage order slip',
                'slug' => 'manage-order-slip',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            //Premium plan features
            [
                'name' => 'Controle de Estoque',
                'description' => 'Stock Management description',
                'slug' => 'stock-management',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cadastro de Entregadores',
                'description' => 'Delivery person registration',
                'slug' => 'delivery-person-registration',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Site de apresentação',
                'description' => 'Presentation site description',
                'slug' => 'site-management',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            //Todos os planos
            [
                'name' => 'Suporte 24/7',
                'description' => '24/7 support description',
                'slug' => 'support',
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ];

        foreach ($features as $feature) {
            \App\Models\Feature::create($feature);
        }
    }
}
