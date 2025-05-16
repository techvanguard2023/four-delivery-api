<?php

namespace App\Services\Reports;

use App\Models\OrderSlip;
use Carbon\Carbon;
use App\Enums\PaymentStatus;

class CounterSaleReportService
{
    public function getCounterSalesTurnover(): array
    {
        return [
            [
                'key' => 'daily_turnover',
                'title' => 'Faturamento Diário',
                'description' => 'Faturamento total das vendas do dia atual.',
                'value' => $this->sumCounterSaleForPeriod('day'),
            ],
            [
                'key' => 'weekly_turnover',
                'title' => 'Faturamento Semanal',
                'description' => 'Faturamento total das vendas da semana atual.',
                'value' => $this->sumCounterSaleForPeriod('week'),
            ],
            [
                'key' => 'monthly_turnover',
                'title' => 'Faturamento Mensal',
                'description' => 'Faturamento total das vendas do mês atual.',
                'value' => $this->sumCounterSaleForPeriod('month'),
            ],
            [
                'key' => 'yearly_turnover',
                'title' => 'Faturamento Anual',
                'description' => 'Faturamento total das vendas do ano atual.',
                'value' => $this->sumCounterSaleForPeriod('year'),
            ]
        ];
    }

    public function getCounterSaleCount(): array
    {
        return [
            [
                'key' => 'daily_orders_slip',
                'title' => 'Vendas do Dia',
                'description' => 'Quantidade de Vendas criadas hoje.',
                'value' => $this->countCounterSaleForPeriod('day'),
            ],
            [
                'key' => 'weekly_orders_slip',
                'title' => 'Vendas da Semana',
                'description' => 'Quantidade de Vendas criadas nesta semana.',
                'value' => $this->countCounterSaleForPeriod('week'),
            ],
            [
                'key' => 'monthly_orders_slip',
                'title' => 'Vendas do Mês',
                'description' => 'Quantidade de Vendas criadas neste mês.',
                'value' => $this->countCounterSaleForPeriod('month'),
            ],
            [
                'key' => 'yearly_orders_slip',
                'title' => 'Vendas do Ano',
                'description' => 'Quantidade de Vendas criadas neste ano.',
                'value' => $this->countCounterSaleForPeriod('year'),
            ],
        ];
    }

    protected function sumCounterSaleForPeriod(string $period)
    {
        $query = $this->queryByPeriod($period);

        // Verifica se há algum valor (não nulo e maior que zero) em total_price_with_discount
        $hasDiscount = (clone $query)
            ->whereNotNull('total_price_with_discount')
            ->limit(1)
            ->exists();

        return $hasDiscount
            ? $query->sum('total_price_with_discount')
            : $query->sum('total_price');
    }


    protected function countCounterSaleForPeriod(string $period)
    {
        return $this->queryByPeriod($period)->count();
    }

    protected function queryByPeriod(string $period)
    {
        $user = auth()->user();
        // Verifica se o usuário está autenticado
        $query = OrderSlip::where('payment_status', PaymentStatus::PAID)
            ->where('company_id', $user->company_id)
            ->where('position', 'counter-sale');


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