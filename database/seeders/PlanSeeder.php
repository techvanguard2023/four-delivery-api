<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
                'price' => 150.00,
                'duration' => 30,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),

            ],
            [
                'name' => 'Profissional',
                'slug' => 'profissional-plan',
                'description' => 'Todos os itens do planos Basico + Adiciona pedidos pelo WhatsApp, comanda digital e pedidos feitos no local.',
                'price' => 200.00,
                'duration' => 30,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Premium',
                'slug' => 'premium-plan',
                'description' => 'Inclui todas as funcionalidades dos outros planos + controle de estoque, cadastro de entregadores e site.',
                'price' => 250.00,
                'duration' => 30,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($plans as $plan) {
            \App\Models\Plan::create($plan);
        }
    }
}
