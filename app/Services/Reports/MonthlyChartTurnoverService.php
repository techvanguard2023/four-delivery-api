<?php

namespace App\Services\Reports;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MonthlyChartTurnoverService
{
    public function getMonthlyChartTurnoverData(): array
    {
        Carbon::setLocale('pt_BR');
        $companyId = auth()->user()->company_id;
        $currentYear = now()->year;

        // Lista fixa de meses
        $months = collect(range(1, 12))->map(function ($month) use ($currentYear) {
            return sprintf('%d-%02d', $currentYear, $month);
        });

        $deliveryCounts = DB::table('orders')
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, SUM(total_price) as total")
            ->where('company_id', $companyId)
            ->where('status_id', 8)
            ->whereYear('created_at', $currentYear)
            ->groupBy('month')
            ->pluck('total', 'month');

        $storeCounts = DB::table('order_slips')
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, SUM(total_price) as total")
            ->where('company_id', $companyId)
            ->where('status_id', '=', 16)
            ->whereYear('created_at', $currentYear)
            ->groupBy('month')
            ->pluck('total', 'month');

        $data = $months->map(function ($month) use ($deliveryCounts, $storeCounts) {
            return [
                'month' => ucfirst(Carbon::createFromFormat('Y-m', $month)->translatedFormat('F')),
                'delivery' => $deliveryCounts[$month] ?? 0,
                'loja' => $storeCounts[$month] ?? 0,
            ];
        })->values()->toArray();

        return [
            'year' => $currentYear,
            'data' => $data,
        ];
    }

}