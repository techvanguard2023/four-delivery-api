<?php

namespace App\Services\Reports;

use App\Models\OrderSlip;
use Carbon\Carbon;
use App\Enums\PaymentStatus;

class OrderSlipReportService
{
    public function getOrderSlipsTurnover(): array
    {
        return [
            [
                'key' => 'daily_turnover',
                'title' => 'Faturamento Diário',
                'description' => 'Faturamento total das comandas do dia atual.',
                'value' => $this->sumOrderSlipForPeriod('day'),
            ],
            [
                'key' => 'weekly_turnover',
                'title' => 'Faturamento Semanal',
                'description' => 'Faturamento total das comandas da semana atual.',
                'value' => $this->sumOrderSlipForPeriod('week'),
            ],
            [
                'key' => 'monthly_turnover',
                'title' => 'Faturamento Mensal',
                'description' => 'Faturamento total das comandas do mês atual.',
                'value' => $this->sumOrderSlipForPeriod('month'),
            ],
            [
                'key' => 'yearly_turnover',
                'title' => 'Faturamento Anual',
                'description' => 'Faturamento total das comandas do ano atual.',
                'value' => $this->sumOrderSlipForPeriod('year'),
            ],
        ];
    }



    public function getOrderSlipCount(): array
    {
        return [
            [
                'key' => 'daily_orders_slip',
                'title' => 'Comandas do Dia',
                'description' => 'Quantidade de comandas criadas hoje.',
                'value' => $this->countOrderSlipForPeriod('day'),
            ],
            [
                'key' => 'weekly_orders_slip',
                'title' => 'Comandas da Semana',
                'description' => 'Quantidade de comandas criadas nesta semana.',
                'value' => $this->countOrderSlipForPeriod('week'),
            ],
            [
                'key' => 'monthly_orders_slip',
                'title' => 'Comandas do Mês',
                'description' => 'Quantidade de comandas criadas neste mês.',
                'value' => $this->countOrderSlipForPeriod('month'),
            ],
            [
                'key' => 'yearly_orders_slip',
                'title' => 'Comandas do Ano',
                'description' => 'Quantidade de comandas criadas neste ano.',
                'value' => $this->countOrderSlipForPeriod('year'),
            ],
        ];
    }



    protected function sumOrderSlipForPeriod(string $period)
    {
        return $this->queryByPeriod($period)->sum('total_price_with_discount' ? 'total_price_with_discount' : 'total_price');
    }

    protected function countOrderSlipForPeriod(string $period)
    {
        return $this->queryByPeriod($period)->count();
    }

    protected function queryByPeriod(string $period)
    {
        $user = auth()->user();
        // Verifica se o usuário está autenticado
        $query = OrderSlip::where('payment_status', PaymentStatus::PAID)
            ->where('company_id', $user->company_id); // <- filtro pela empresa do usuário


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
