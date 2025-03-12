<?php

namespace Database\Seeders;

use App\Models\Feature;
use Illuminate\Database\Seeder;

class FeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $features = [
            // Essencial plan features
            ['name' => 'Dashboard Financeiro', 'description' => 'Financial dashboard', 'slug' => 'financial-dashboard'],
            ['name' => 'Menu Digital', 'description' => 'Digital menu', 'slug' => 'digital-menu'],
            ['name' => 'Cadastro de Clientes', 'description' => 'Customer registration', 'slug' => 'customer-registration'],
            ['name' => 'Gerenciar pedidos delivery', 'description' => 'Manage delivery orders', 'slug' => 'manage-delivery-orders'],

            // Professional plan features
            ['name' => 'Gerenciar pedidos pelo Whatsapp', 'description' => 'Manage Whatsapp orders', 'slug' => 'manage-whatsapp-orders'],
            ['name' => 'Gerenciar pedidos no local', 'description' => 'Manage local orders', 'slug' => 'manage-local-orders'],
            ['name' => 'Gerenciar Comanda Digital', 'description' => 'Manage order slip', 'slug' => 'manage-order-slip'],

            // Premium plan features
            ['name' => 'Controle de Estoque', 'description' => 'Stock management', 'slug' => 'stock-management'],
            ['name' => 'Cadastro de Entregadores', 'description' => 'Delivery person registration', 'slug' => 'delivery-person-registration'],
            ['name' => 'Site de apresentação', 'description' => 'Presentation site', 'slug' => 'site-management'],

            // Todos os planos
            ['name' => 'Suporte 24/7', 'description' => '24/7 support', 'slug' => 'support'],
        ];

        foreach ($features as $feature) {
            Feature::updateOrCreate(
                ['slug' => $feature['slug']], // Verifica se já existe pelo slug
                $feature
            );
        }
    }
}
