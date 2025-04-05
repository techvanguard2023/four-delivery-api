<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Reports\DeliveryReportService;
use Illuminate\Http\Request;
use Carbon\Carbon;



class StatsController extends Controller
{

    protected $service;

    public function __construct(DeliveryReportService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return [
            'store' => [
                'store_turnover' => [
                    'daily turnover' => 100,
                    'weekly turnover' => 500,
                    'monthly turnover' => 1000,
                    'yearly turnover' => 12000,
                ],
                'store_orders' => [
                    'daily orders' => 10,
                    'weekly orders' => 50,
                    'monthly orders' => 100,
                    'yearly orders' => 1200,
                ],
            ],
            'delivery' => [
                'delivery_turnover' => $this->service->getTurnover(),
                'delivery_orders' => $this->service->getOrdersCount(),
            ]
        ];
    }
}
