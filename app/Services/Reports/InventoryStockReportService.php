<?php

namespace App\Services\Reports;

use App\Models\Category;
use App\Models\Item;
use App\Models\Stock;
use App\Models\OrderItem;
use App\Models\OrderSlipItem;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;

class InventoryStockReportService
{
    public function getReport()
    {
        return [
            'total_categories' => $this->getTotalCategories(),
            'total_items' => $this->getTotalItems(),
            'low_stock_items' => $this->getLowStockItems(),
            'top_selling_items' => $this->getTopSellingItems(),
            'top_selling_items_delivery' => $this->getTopSellingItemsByOrderType('Delivery'),
            'top_selling_items_store' => $this->getTopSellingItemsByOrderType('Consumo no local'),
        ];
    }

    protected function getTotalCategories()
    {
        return Category::count();
    }

    protected function getTotalItems()
    {
        $companyId = auth()->user()->company_id;

        return Item::where('company_id', $companyId)->count();
    }

    protected function getLowStockItems()
{
    $companyId = auth()->user()->company_id;

    $settings = Setting::where('company_id', $companyId)->first();
    $data = json_decode($settings->data, true);
    $stockAlert = data_get($data, 'stock_alert', 20);

    return Stock::with('item')
        ->whereHas('item', fn($q) => $q->where('company_id', $companyId))
        ->where('quantity', '<', $stockAlert)
        ->get();
}


    protected function getTopSellingItems()
    {
        $companyId = auth()->user()->company_id;

        $orderItems = OrderItem::select('item_id', DB::raw('SUM(quantity) as total'))
            ->whereHas('item', fn($q) => $q->where('company_id', $companyId))
            ->groupBy('item_id')
            ->orderByDesc('total')
            ->with('item')
            ->take(5)
            ->get();

        $orderSlipItems = OrderSlipItem::select('item_id', DB::raw('SUM(quantity) as total'))
            ->whereHas('item', fn($q) => $q->where('company_id', $companyId))
            ->groupBy('item_id')
            ->orderByDesc('total')
            ->with('item')
            ->take(5)
            ->get();

        return $orderItems->merge($orderSlipItems)->sortByDesc('total')->take(5)->values();
    }

    protected function getTopSellingItemsByOrderType(string $type)
    {
        $companyId = auth()->user()->company_id;

        $orderTypeId = DB::table('order_types')->where('name', $type)->value('id');

        $model = $type === 'Delivery' ? OrderItem::class : OrderSlipItem::class;

        return $model::select('item_id', DB::raw('SUM(quantity) as total'))
            ->whereHas('item', fn($q) => $q->where('company_id', $companyId))
            ->whereHas($type === 'Delivery' ? 'order' : 'orderSlip', fn($q) => $q->where('order_type_id', $orderTypeId))
            ->groupBy('item_id')
            ->orderByDesc('total')
            ->with('item')
            ->take(5)
            ->get();
    }
}