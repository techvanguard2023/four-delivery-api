<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Essencial',
                'slug' => 'essencial-plan',
                'description' => 'Inclui recursos básicos como cadastro de clientes, pedidos, dashboard financeiro e cardápio digital.',
                'price' => 99.90,
                'stripe_price_id' => 'price_1SOK9CIaNFIpHqr0fEz21wtf',
                'duration' => 30,
                'status' => 'active',
                'is_free' => false,
                'is_popular' => false,
            ],
            [
                'name' => 'Profissional',
                'slug' => 'profissional-plan',
                'description' => 'Todos os itens do plano Essencial + pedidos pelo WhatsApp, comanda digital e pedidos feitos no local.',
                'price' => 149.90,
                'stripe_price_id' => 'price_1SOKASIaNFIpHqr0DxBw1vTQ',
                'duration' => 30,
                'status' => 'active',
                'is_free' => false,
                'is_popular' => true,
            ],
            [
                'name' => 'Premium',
                'slug' => 'premium-plan',
                'description' => 'Inclui todas as funcionalidades dos outros planos + controle de estoque, cadastro de entregadores e site.',
                'price' => 199.90,
                'stripe_price_id' => 'price_1SOKBSIaNFIpHqr0zFQxzPUn',
                'duration' => 30,
                'status' => 'active',
                'is_free' => false,
                'is_popular' => false,
            ],
        ];

        foreach ($plans as $plan) {
            Plan::updateOrCreate(
                ['slug' => $plan['slug']], // Condição para evitar duplicação
                array_merge($plan, ['updated_at' => now()]) // Atualiza caso já exista
            );
        }
    }
}
