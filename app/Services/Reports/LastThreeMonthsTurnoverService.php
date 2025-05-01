<?php

namespace App\Services\Reports;

use App\Models\OrderSlip;
use App\Models\Order;
use Carbon\Carbon;
use App\Enums\OrderStatus;

class LastThreeMonthsTurnoverService
{
    public function getTurnover(int $companyId): array
    {
        $startDate = Carbon::now()->subDays(89)->startOfDay(); // últimos 90 dias corridos
        $endDate = Carbon::now()->endOfDay();

        $storeData = OrderSlip::selectRaw("DATE(created_at) as date, SUM(total_price_with_discount) as total")
            ->where('company_id', $companyId)
            ->where('status_id', OrderStatus::CLOSED_ORDER_SLIP)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupByRaw('DATE(created_at)')
            ->get()
            ->mapWithKeys(fn($item) => [$item->date => $item->total]);

        $deliveryData = Order::selectRaw("DATE(created_at) as date, SUM(total_price) as total")
            ->where('company_id', $companyId)
            ->where('status_id', OrderStatus::ORDER_DELIVERED)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupByRaw('DATE(created_at)')
            ->get()
            ->mapWithKeys(fn($item) => [$item->date => $item->total]);

        $result = [];

        for ($i = 0; $i < 90; $i++) {
            $date = Carbon::now()->subDays(89 - $i)->format('Y-m-d');

            $result[] = [
                'date' => $date,
                'Loja' => ($storeData[$date] ?? 0),
                'Delivery' => ($deliveryData[$date] ?? 0),
            ];
        }

        return $result;
    }
}
