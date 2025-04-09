<?php

namespace App\Services\Reports;

use App\Models\Customer;

class RecentCustomersService
{
    public function getLastCustomers(int $limit = 3)
    {
        $companyId = auth()->user()->company_id;

        return Customer::where('company_id', $companyId)
            ->orderByDesc('created_at')
            ->take($limit)
            ->get(['id', 'name', 'phone', 'is_whatsapp', 'status', 'created_at']);
    }

    public function getTotalCustomers(): int
    {
        $companyId = auth()->user()->company_id;

        return Customer::where('company_id', $companyId)->count();
    }
}