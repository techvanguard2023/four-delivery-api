<?php

namespace Database\Seeders;

use App\Models\Feature;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $now = now();

        $features = [
            //Essencial
            [
                'name' => 'Cadastro de até 30 produtos',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'name' => 'Controler de comandas por mesas',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'name' => 'Dashboard Quantitativo',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'name' => 'Controle de estoque',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'name' => 'Cadastro de usuários com permissões',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'name' => 'Suporte por email no horário comercial',
                'created_at' => $now, 'updated_at' => $now,
            ],

            //Profissional
            [
                'name' => 'Cadastro de até 100 produtos',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'name' => 'Controler de comandas por mesas',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'name' => 'Dashboard Quantitativo',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'name' => 'Dashboard Financeiro',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'name' => 'Relatórios de vendas',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'name' => 'Cardápio digital',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'name' => 'Comanda digital',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'name' => 'Pedidos feitos no local',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'name' => 'Controle de estoque',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'name' => 'Cadastro de usuários com permissões',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'name' => 'Cadastro de Clientes',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'name' => 'Suporte por chat no horário comercial',
                'created_at' => $now, 'updated_at' => $now,
            ],

            //Premium
            [
                'name' => 'Cadastro de até 300 produtos',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'name' => 'Controler de comandas por mesas',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'name' => 'Dashboard Quantitativo',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'name' => 'Dashboard Financeiro',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'name' => 'Relatórios de vendas',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'name' => 'Cardápio digital',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'name' => 'Comanda digital',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'name' => 'Pedidos feitos no local',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'name' => 'Controle de estoque',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'name' => 'Cadastro de usuários com permissões',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'name' => 'Cadastro de Clientes',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'name' => 'Controle de entregadores',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'name' => 'Tela de pedidos',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'name' => 'Pedidos pelo WhatsApp',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'name' => 'Bot de atendimento',
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'name' => 'Suporte 24/7*',
                'created_at' => $now, 'updated_at' => $now,
            ],
        ];

        DB::table('features')->upsert(
            $features,
            ['name'],
            ['updated_at']
        );
    }
}
