<?php

namespace App\Services\Reports;

use App\Models\Setting;
use App\Models\OrderSlip;
use Carbon\Carbon;

class AvailableTablesReportService
{
    public function getAvailableTables()
    {
        $companyId = auth()->user()->company_id;

        // 1. Pegar total de mesas do JSON
        $settings = Setting::where('company_id', $companyId)->first();
        $data = is_array($settings->data)
            ? $settings->data
            : json_decode($settings->data, true);

        $totalTables = data_get($data, 'total_tables', 0);

        // 2. Buscar todas as comandas ocupando mesas hoje
        $occupiedOrderSlips = OrderSlip::where('company_id', $companyId)
            ->whereDate('created_at', Carbon::today())
            ->where('payment_status', '!=', 'paid')
            ->whereNotNull('position')
            ->where('position', 'like', 'Mesa %')
            ->get();

        // 3. Agrupar por mesa e pegar a comanda mais antiga de cada
        $tablesGrouped = $occupiedOrderSlips
            ->groupBy('position')
            ->map(function ($orderSlips) {
                return $orderSlips->sortBy('created_at')->first(); // Mais antiga
            });

        $occupiedCount = $tablesGrouped->count();
        $availableTables = max($totalTables - $occupiedCount, 0);

        $occupancyRate = $totalTables > 0
            ? round(($occupiedCount / $totalTables) * 100, 2)
            : 0;

        // 4. Montar detalhes das mesas em uso
        $tablesInUse = $tablesGrouped->values()->map(function ($orderSlip) {
            $startedAt = $orderSlip->created_at;
            $duration = $startedAt->diffInMinutes(now());

            $hours = floor($duration / 60);
            $minutes = $duration % 60;
            $formatted = $hours > 0
                ? "{$hours}h " . ($minutes > 0 ? "{$minutes}min" : '')
                : "{$minutes}min";

            return [
                'position' => $orderSlip->position,
                'started_at' => $startedAt->toDateTimeString(),
                'duration_minutes' => $duration,
                'formatted_duration' => $formatted,
                'customer_name' => $orderSlip->customer_name ?? null,
                'status_id' => $orderSlip->status_id,
                'payment_status' => $orderSlip->payment_status,
            ];
        });

        return [
            'details' => [
                [
                    'title' => 'Total de Mesas',
                    'value' => $totalTables,
                ],
                [
                    'title' => 'Mesas Ocupadas',
                    'value' => $occupiedCount,
                ],
                [
                    'title' => 'Mesas Disponíveis',
                    'value' => $availableTables,
                ],
                [
                    'title' => 'Taxa de Ocupação',
                    'value' => "{$occupancyRate}%",
                ],
            ],
            'summary' => [
                'total_tables' => $totalTables,
                'occupied_tables' => $occupiedCount,
                'available_tables' => $availableTables,
                'occupancy_rate' => $occupancyRate,
            ],
            'tables_in_use' => $tablesInUse,
        ];
    }
}