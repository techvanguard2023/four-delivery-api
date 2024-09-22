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
                'name' => 'Free',
                'slug' => 'free-plan',
                'description' => 'Free plan',
                'price' => 0,
                'duration' => 0,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),

            ],
            [
                'name' => 'Basic',
                'slug' => 'basic-plan',
                'description' => 'Basic plan',
                'price' => 9.99,
                'duration' => 30,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Premium',
                'slug' => 'premium-plan',
                'description' => 'Premium plan',
                'price' => 19.99,
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
