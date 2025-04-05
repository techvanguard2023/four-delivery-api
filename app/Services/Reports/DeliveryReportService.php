<?php

namespace App\Services\Reports;

use App\Models\Order;
use Carbon\Carbon;

class DeliveryReportService
{
    public function getTurnover(): array
    {
        return [
            'daily turnover' => $this->sumOrdersForPeriod('day'),
            'weekly turnover' => $this->sumOrdersForPeriod('week'),
            'monthly turnover' => $this->sumOrdersForPeriod('month'),
            'yearly turnover' => $this->sumOrdersForPeriod('year'),
        ];
    }

    public function getOrdersCount(): array
    {
        return [
            'daily orders' => $this->countOrdersForPeriod('day'),
            'weekly orders' => $this->countOrdersForPeriod('week'),
            'monthly orders' => $this->countOrdersForPeriod('month'),
            'yearly orders' => $this->countOrdersForPeriod('year'),
        ];
    }

    protected function sumOrdersForPeriod(string $period)
    {
        return $this->queryByPeriod($period)->sum('total_price');
    }

    protected function countOrdersForPeriod(string $period)
    {
        return $this->queryByPeriod($period)->count();
    }

    protected function queryByPeriod(string $period)
    {
        $query = Order::where('order_type_id', 2)
            ->where('company_id', auth()->user()->company_id); // <- filtro pela empresa do usuário


        switch ($period) {
            case 'day':
                return $query->whereDate('created_at', Carbon::today());
            case 'week':
                return $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), now()]);
            case 'month':
                return $query->whereBetween('created_at', [Carbon::now()->startOfMonth(), now()]);
            case 'year':
                return $query->whereBetween('created_at', [Carbon::now()->startOfYear(), now()]);
            default:
                return $query;
        }
    }
}
