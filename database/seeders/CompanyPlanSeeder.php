<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanyPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companyPlans = [
            [
                'company_id' => 1,
                'plan_id' => 1,
                'start_date' => now(),
                'end_date' => now()->addDays(30),
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => 2,
                'plan_id' => 2,
                'start_date' => now(),
                'end_date' => now()->addDays(30),
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        foreach ($companyPlans as $companyPlan) {
            \App\Models\CompanyPlan::create($companyPlan);
        }
    }
}
