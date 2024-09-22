<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanFeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $planFeatures = [
            [
                'plan_id' => 1,
                'feature_id' => 1,
                'name' => 'Controle de Estoque',
                'description' => 'Stock Management description',
                'position' => 0,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'plan_id' => 1,
                'feature_id' => 2,
                'name' => 'Site de apresentação',
                'description' => 'Presentation site description',
                'position' => 1,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'plan_id' => 1,
                'feature_id' => 3,
                'name' => 'Bot de atendimento ao cliente pelo Whatsapp',
                'description' => 'Customer service bot via Whatsapp description',
                'position' => 2,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'plan_id' => 1,
                'feature_id' => 4,
                'name' => 'Suporte 24/7',
                'description' => '24/7 support description',
                'position' => 3,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'plan_id' => 2,
                'feature_id' => 3,
                'name' => 'Bot de atendimento ao cliente pelo Whatsapp',
                'description' => 'Customer service bot via Whatsapp description',
                'position' => 0,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'plan_id' => 2,
                'feature_id' => 4,
                'name' => 'Suporte 24/7',
                'description' => '24/7 support description',
                'position' => 1,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'plan_id' => 3,
                'feature_id' => 1,
                'name' => 'Controle de Estoque',
                'description' => 'Stock Management description',
                'position' => 0,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'plan_id' => 3,
                'feature_id' => 2,
                'name' => 'Site de apresentação',
                'description' => 'Presentation site description',
                'position' => 1,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'plan_id' => 3,
                'feature_id' => 3,
                'name' => 'Bot de atendimento ao cliente pelo Whatsapp',
                'description' => 'Customer service bot via Whatsapp description',
                'position' => 2,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'plan_id' => 3,
                'feature_id' => 4,
                'name' => 'Suporte 24/7',
                'description' => '24/7 support description',
                'position' => 3,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ];

        foreach ($planFeatures as $planFeature) {
            \App\Models\PlanFeature::create($planFeature);
        }
    }
}
