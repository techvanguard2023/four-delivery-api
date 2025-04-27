<?php

namespace App\Services\Reports;

use App\Models\Order;
use Carbon\Carbon;
use App\Enums\OrderStatus;



class DeliveryReportService
{
    public function getTurnover(): array
    {
        return [
            [
                'key' => 'daily_turnover',
                'title' => 'Faturamento Diário',
                'description' => 'Total faturado com entregas no dia atual.',
                'value' => $this->sumOrdersForPeriod('day'),
            ],
            [
                'key' => 'weekly_turnover',
                'title' => 'Faturamento Semanal',
                'description' => 'Total faturado com entregas na semana atual.',
                'value' => $this->sumOrdersForPeriod('week'),
            ],
            [
                'key' => 'monthly_turnover',
                'title' => 'Faturamento Mensal',
                'description' => 'Total faturado com entregas no mês atual.',
                'value' => $this->sumOrdersForPeriod('month'),
            ],
            [
                'key' => 'yearly_turnover',
                'title' => 'Faturamento Anual',
                'description' => 'Total faturado com entregas no ano atual.',
                'value' => $this->sumOrdersForPeriod('year'),
            ],
        ];
    }



    public function getOrdersCount(): array
        {
            return [
                [
                    'key' => 'daily_orders',
                    'title' => 'Pedidos do Dia',
                    'description' => 'Total de pedidos realizados hoje.',
                    'value' => $this->countOrdersForPeriod('day'),
                ],
                [
                    'key' => 'weekly_orders',
                    'title' => 'Pedidos da Semana',
                    'description' => 'Total de pedidos realizados nesta semana.',
                    'value' => $this->countOrdersForPeriod('week'),
                ],
                [
                    'key' => 'monthly_orders',
                    'title' => 'Pedidos do Mês',
                    'description' => 'Total de pedidos realizados neste mês.',
                    'value' => $this->countOrdersForPeriod('month'),
                ],
                [
                    'key' => 'yearly_orders',
                    'title' => 'Pedidos do Ano',
                    'description' => 'Total de pedidos realizados neste ano.',
                    'value' => $this->countOrdersForPeriod('year'),
                ],
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
        $user = auth()->user();
        // Verifica se o usuário está autenticado
        $query = Order::where('status_id', OrderStatus::ORDER_DELIVERED)
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