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
            //Essencial plan features
            [
                'plan_id' => 1,
                'feature_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'plan_id' => 1,
                'feature_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'plan_id' => 1,
                'feature_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'plan_id' => 1,
                'feature_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'plan_id' => 1,
                'feature_id' => 11,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            //Professional plan features
            [
                'plan_id' => 2,
                'feature_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'plan_id' => 2,
                'feature_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'plan_id' => 2,
                'feature_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'plan_id' => 2,
                'feature_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'plan_id' => 2,
                'feature_id' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'plan_id' => 2,
                'feature_id' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'plan_id' => 2,
                'feature_id' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'plan_id' => 2,
                'feature_id' => 11,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            //Premium plan features
            [
                'plan_id' => 3,
                'feature_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'plan_id' => 3,
                'feature_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'plan_id' => 3,
                'feature_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'plan_id' => 3,
                'feature_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'plan_id' => 3,
                'feature_id' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'plan_id' => 3,
                'feature_id' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'plan_id' => 3,
                'feature_id' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'plan_id' => 3,
                'feature_id' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'plan_id' => 3,
                'feature_id' => 9,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'plan_id' => 3,
                'feature_id' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'plan_id' => 3,
                'feature_id' => 11,
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ];

        foreach ($planFeatures as $planFeature) {
            \App\Models\PlanFeature::create($planFeature);
        }
    }
}
