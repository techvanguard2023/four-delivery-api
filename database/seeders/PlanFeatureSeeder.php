<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanFeatureSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $map = [
            'Essencial' => [
                'Cadastro de até 30 produtos',
                'Controler de comandas por mesas',
                'Dashboard Quantitativo',
                'Controle de estoque',
                'Cadastro de usuários com permissões',
                'Suporte por email no horário comercial',
            ],
            'Profissional' => [
                'Cadastro de até 100 produtos',
                'Controler de comandas por mesas',
                'Dashboard Quantitativo',
                'Dashboard Financeiro',
                'Relatórios de vendas',
                'Cardápio digital',
                'Comanda digital',
                'Pedidos feitos no local',
                'Controle de estoque',
                'Cadastro de usuários com permissões',
                'Cadastro de Clientes',
                'Suporte por chat no horário comercial',

            ],
            'Premium' => [
                'Cadastro de até 300 produtos',
                'Controler de comandas por mesas',
                'Dashboard Quantitativo',
                'Dashboard Financeiro',
                'Relatórios de vendas',
                'Cardápio digital',
                'Comanda digital',
                'Pedidos feitos no local',
                'Controle de estoque',
                'Cadastro de usuários com permissões',
                'Cadastro de Clientes',
                'Controle de entregadores',
                'Tela de pedidos',
                'Pedidos pelo WhatsApp',
                'Bot de atendimento',
                'Suporte 24/7*',
            ],
        ];

        // Busca IDs dos planos pelo nome
        $planIds = DB::table('plans')
            ->whereIn('name', array_keys($map))
            ->pluck('id', 'name');

        // Busca IDs das features pelo nome (únicas no seu seeder)
        $allFeatureNames = collect($map)->flatten()->unique()->values()->all();
        $featureIds = DB::table('features')
            ->whereIn('name', $allFeatureNames)
            ->pluck('id', 'name'); 

        // Monta linhas do pivot
        $rows = [];
        foreach ($map as $planName => $features) {
            $planId = $planIds[$planName] ?? null;
            if (!$planId) {
                continue;
            }

            foreach ($features as $featureName) {
                $featureId = $featureIds[$featureName] ?? null;
                if (!$featureId) {
                    continue; // feature não encontrada
                }

                $rows[] = [
                    'plan_id'    => $planId,
                    'feature_id' => $featureId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        if (empty($rows)) {
            return;
        }

        // Remove vínculos antigos desses planos (evita lixo ao re-seedar)
        DB::table('plan_features')->whereIn('plan_id', $planIds->values())->delete();

        // Insere vínculos
        DB::table('plan_features')->insert($rows);
    }
}