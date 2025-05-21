<?php

namespace App\Services\Reports;

use Illuminate\Support\Facades\DB;

class PaymentMethodReportService
{
    public function getMonthlyPaymentMethod(int $year = null): array
    {
        $year = $year ?? now()->year;

        $rawData = DB::table('payments')
            ->select(
                DB::raw("YEAR(created_at) as year"),
                DB::raw("MONTH(created_at) as month_number"),
                DB::raw("SUM(CASE WHEN payment_method_id = 1 THEN amount ELSE 0 END) as dinheiro"),
                DB::raw("SUM(CASE WHEN payment_method_id = 2 THEN amount ELSE 0 END) as cartao_credito"),
                DB::raw("SUM(CASE WHEN payment_method_id = 3 THEN amount ELSE 0 END) as cartao_debito"),
                DB::raw("SUM(CASE WHEN payment_method_id = 4 THEN amount ELSE 0 END) as pix")
            )
            ->where('company_id', auth()->user()->company_id)
            ->whereNull('deleted_at')
            ->whereYear('created_at', $year)
            ->groupBy('year', 'month_number')
            ->orderBy('month_number')
            ->get();

        $monthNames = [
            1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
            5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
            9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
        ];

        $formattedData = $rawData->map(function ($item) use ($monthNames) {
            return [
                'month' => $monthNames[(int)$item->month_number],
                'dinheiro' => number_format($item->dinheiro, 2),
                'cartao_credito' => number_format($item->cartao_credito, 2),
                'cartao_debito' => number_format($item->cartao_debito, 2),
                'pix' => number_format($item->pix, 2),
            ];
        });

        return [
            'year' => $year,
            'data' => $formattedData
        ];
    }

    public function getMonthlyPaymentMethodCount(int $year = null): array
    {
        $year = $year ?? now()->year;

        $rawData = DB::table('payments')
            ->select(
                DB::raw("YEAR(created_at) as year"),
                DB::raw("MONTH(created_at) as month_number"),
                DB::raw("COUNT(CASE WHEN payment_method_id = 1 THEN 1 END) as dinheiro"),
                DB::raw("COUNT(CASE WHEN payment_method_id = 2 THEN 1 END) as cartao_credito"),
                DB::raw("COUNT(CASE WHEN payment_method_id = 3 THEN 1 END) as cartao_debito"),
                DB::raw("COUNT(CASE WHEN payment_method_id = 4 THEN 1 END) as pix")
            )
            ->where('company_id', auth()->user()->company_id)
            ->whereNull('deleted_at')
            ->whereYear('created_at', $year)
            ->groupBy('year', 'month_number')
            ->orderBy('month_number')
            ->get();

        $monthNames = [
            1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
            5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
            9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
        ];

        $formattedData = $rawData->map(function ($item) use ($monthNames) {
            return [
                'month' => $monthNames[(int)$item->month_number],
                'dinheiro' => (int) $item->dinheiro,
                'cartao_credito' => (int) $item->cartao_credito,
                'cartao_debito' => (int) $item->cartao_debito,
                'pix' => (int) $item->pix,
            ];
        });

        return [
            'year' => $year,
            'data' => $formattedData
        ];
    }

}
