<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CompanyPlan;
use App\Models\Company;
use App\Models\Plan;

class CompanyPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companyPlans = [
            ['company_id' => 1, 'plan_id' => 3],
            ['company_id' => 2, 'plan_id' => 2],
            ['company_id' => 3, 'plan_id' => 1],
        ];

        foreach ($companyPlans as $companyPlan) {
            // Verifica se a empresa e o plano existem
            $company = Company::find($companyPlan['company_id']);
            $plan = Plan::find($companyPlan['plan_id']);

            if ($company && $plan) {
                CompanyPlan::updateOrCreate(
                    ['company_id' => $company->id, 'plan_id' => $plan->id], // Critério para evitar duplicação
                    [
                        'start_date' => now(),
                        'end_date' => now()->addDays(30),
                        'status' => 'active',
                    ]
                );
            }
        }
    }
}
