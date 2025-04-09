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

    public function __construct(
        DeliveryReportService $deliveryService,
        OrderSlipReportService $orderSlipService,
        AvailableTablesReportService $availableTablesService,
        MonthlyChartService $chartService,
        RecentCustomersService $recentCustomersService,
        InventoryStockReportService $inventoryStockReportService,
        ReservationReportService $reservationReportService,
    ) {
        $this->deliveryService = $deliveryService;
        $this->orderSlipService = $orderSlipService;
        $this->availableTablesService = $availableTablesService;
        $this->chartService = $chartService;
        $this->recentCustomersService = $recentCustomersService;
        $this->inventoryStockReportService = $inventoryStockReportService;
        $this->reservationReportService = $reservationReportService;
    }

    public function index()
    {
        $companyId = auth()->user()->company_id;

        return [
            'store' => [
                'orderslip_turnover' => $this->orderSlipService->getOrderSlipsTurnover(),
                'orderslip' => $this->orderSlipService->getOrderSlipCount(),
                'tables' => $this->availableTablesService->getAvailableTables(),
                'reservations' => $this->reservationReportService->getTodayReservations(), //verificar a criacao de reservas no front
            ],
            'delivery' => [
                'delivery_turnover' => $this->deliveryService->getTurnover(),
                'delivery_orders' => $this->deliveryService->getOrdersCount(),
                'recent_customers' => $this->recentCustomersService->getLastCustomers(),
                'total_customers' => $this->recentCustomersService->getTotalCustomers(),
            ],
            'stock' => $this->inventoryStockReportService->getReport($companyId),
            'chart' => $this->chartService->getMonthlyChartData(),
        ];
    }
}