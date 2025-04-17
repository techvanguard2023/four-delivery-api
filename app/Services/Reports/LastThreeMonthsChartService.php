<?php

namespace App\Services\Reports;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LastThreeMonthsChartService
{
    public function getSalesChartData(): array
    {
        $companyId = auth()->user()->company_id;

        // Período de 3 meses a partir de hoje para trás
        $endDate = Carbon::now()->endOfDay();
        $startDate = Carbon::now()->subMonths(3)->startOfDay();

        // Cria um período com todos os dias entre startDate e endDate
        $datePeriod = collect();
        $currentDate = $startDate->copy();
        while ($currentDate->lte($endDate)) {
            $datePeriod->push($currentDate->format('Y-m-d'));
            $currentDate->addDay();
        }

        // Agrupar pedidos de delivery por dia
        $deliveryData = DB::table('orders')
            ->selectRaw("DATE(created_at) as date, COUNT(*) as total")
            ->where('company_id', $companyId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->pluck('total', 'date');

        // Agrupar pedidos de loja por dia
        $storeData = DB::table('order_slips')
            ->selectRaw("DATE(created_at) as date, COUNT(*) as total")
            ->where('company_id', $companyId)
            ->where('status_id', 16)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->pluck('total', 'date');

        // Monta os dados no formato especificado
        $chartData = $datePeriod->map(function ($date) use ($storeData, $deliveryData) {
            return [
                'date' => $date,
                'Loja' => ($storeData[$date] ?? 0),
                'Delivery' => ($deliveryData[$date] ?? 0),
            ];
        });

        return $chartData->toArray();
    }
}
