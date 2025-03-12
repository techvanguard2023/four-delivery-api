<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PlanFeature;
use App\Models\Plan;
use App\Models\Feature;

class PlanFeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            1 => [1, 2, 3, 4, 11],             // Essencial
            2 => [1, 2, 3, 4, 5, 6, 7, 11],    // Professional
            3 => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11], // Premium
        ];

        foreach ($plans as $planId => $features) {
            $plan = Plan::find($planId);
            if (!$plan) continue; // Se o plano não existir, pula para o próximo

            foreach ($features as $featureId) {
                $feature = Feature::find($featureId);
                if (!$feature) continue; // Se a feature não existir, pula

                PlanFeature::updateOrCreate(
                    ['plan_id' => $plan->id, 'feature_id' => $feature->id],
                    [] // Sem necessidade de valores adicionais
                );
            }
        }
    }
}
