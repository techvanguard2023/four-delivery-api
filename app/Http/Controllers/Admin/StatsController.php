<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Reports\DeliveryReportService;
use App\Services\Reports\OrderSlipReportService;
use App\Services\Reports\AvailableTablesReportService;
use App\Services\Reports\MonthlyChartService;
use App\Services\Reports\RecentCustomersService;
use App\Services\Reports\InventoryStockReportService;
use App\Services\Reports\ReservationReportService;
use App\Services\Reports\LastThreeMonthsChartService;
use App\Services\Reports\LastThreeMonthsTurnoverService;
use App\Services\Reports\MonthlyChartTurnoverService;

use Illuminate\Http\Request;

class StatsController extends Controller
{
    protected DeliveryReportService $deliveryService;
    protected OrderSlipReportService $orderSlipService;
    protected AvailableTablesReportService $availableTablesService;
    protected MonthlyChartService $chartService;
    protected RecentCustomersService $recentCustomersService;
    protected InventoryStockReportService $inventoryStockReportService;
    protected ReservationReportService $reservationReportService;
    protected LastThreeMonthsChartService $lastThreeMonthsChartService;
    protected LastThreeMonthsTurnoverService $lastThreeMonthsTurnoverService;
    protected MonthlyChartTurnoverService $monthlyChartTurnoverService;

    public function __construct(
        DeliveryReportService $deliveryService,
        OrderSlipReportService $orderSlipService,
        AvailableTablesReportService $availableTablesService,
        MonthlyChartService $chartService,
        RecentCustomersService $recentCustomersService,
        InventoryStockReportService $inventoryStockReportService,
        ReservationReportService $reservationReportService,
        LastThreeMonthsChartService $lastThreeMonthsChartService,
        LastThreeMonthsTurnoverService $lastThreeMonthsTurnoverService,
        MonthlyChartTurnoverService $monthlyChartTurnoverService
    ) {
        $this->deliveryService = $deliveryService;
        $this->orderSlipService = $orderSlipService;
        $this->availableTablesService = $availableTablesService;
        $this->chartService = $chartService;
        $this->recentCustomersService = $recentCustomersService;
        $this->inventoryStockReportService = $inventoryStockReportService;
        $this->reservationReportService = $reservationReportService;
        $this->lastThreeMonthsChartService = $lastThreeMonthsChartService;
        $this->lastThreeMonthsTurnoverService = $lastThreeMonthsTurnoverService;
        $this->monthlyChartTurnoverService = $monthlyChartTurnoverService;
    }

    public function index()
    {
        $companyId = auth()->user()->company_id;

        return [
            'store' => [
                'orderslip_turnover' => $this->orderSlipService->getOrderSlipsTurnover(),
                'orderslip' => $this->orderSlipService->getOrderSlipCount(),
                'tables' => $this->availableTablesService->getAvailableTables(),
                'reservations' => $this->reservationReportService->getTodayReservations(),

            ],
            'delivery' => [
                'delivery_turnover' => $this->deliveryService->getTurnover(),
                'delivery_orders' => $this->deliveryService->getOrdersCount(),
                'recent_customers' => $this->recentCustomersService->getLastCustomers(),
                'total_customers' => $this->recentCustomersService->getTotalCustomers(),
            ],
            'stock' => $this->inventoryStockReportService->getReport($companyId),
            'chart' => $this->chartService->getMonthlyChartData(),
            'monthly_chart_turnover' => $this->monthlyChartTurnoverService->getMonthlyChartTurnoverData(),
            'last_three_months' => $this->lastThreeMonthsChartService->getSalesChartData(),
            'last_three_months_turnover' => $this->lastThreeMonthsTurnoverService->getTurnover($companyId),
        ];
    }
}
