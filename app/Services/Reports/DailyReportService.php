<?php

namespace App\Services\Reports;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderSlip;
use App\Models\OrderSlipItem;
use App\Models\Payment;
use App\Models\Item;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;

class DailyReportService
{
    public function getReport(int $companyId, $date): array
    {
        $start = Carbon::parse($date)->startOfDay();
        $end = Carbon::parse($date)->endOfDay();

        // === OrderSlips (Mesa / Balcão) ===
        $orderSlipQuery = OrderSlip::whereBetween('created_at', [$start, $end]);
        $orderslipCount = (clone $orderSlipQuery)->count();
        $orderslipTurnover = (clone $orderSlipQuery)->sum('total_price_with_discount');

        // === Orders (Delivery / Balcão) ===
        $orderQuery = Order::whereBetween('created_at', [$start, $end]);

        // Delivery
        $deliveryOrders = (clone $orderQuery);
        $deliveryCount = (clone $deliveryOrders)->count();
        $deliveryTurnover = (clone $deliveryOrders)->sum('total_price');

        // Counter Sales
        $counterOrders = (clone $orderSlipQuery)->where('position', 'counter-sale');
        $counterCount = (clone $counterOrders)->count();
        $counterTurnover = (clone $counterOrders)->sum('total_price');

        // === Pagamentos ===
        $payments = Payment::whereBetween('created_at', [$start, $end])->get();
        $paymentMethodTurnover = [];
        $paymentMethodCount = [];

        foreach ($payments as $payment) {
            $methodName = $payment->paymentMethod->name ?? 'Desconhecido';
            $key = Str::slug($methodName, '_'); // "Cartão de Crédito" -> "cartao_de_credito"
        
            $paymentMethodTurnover[$key] = ($paymentMethodTurnover[$key] ?? 0) + $payment->amount;
            $paymentMethodCount[$key] = ($paymentMethodCount[$key] ?? 0) + 1;
        }

        // === Itens vendidos ===
        $orderItems = OrderItem::whereHas('order', fn ($q) => $q->whereBetween('created_at', [$start, $end]))
            ->select('item_id', DB::raw('SUM(quantity) as total_quantity'), DB::raw('SUM(price * quantity) as total_price'))
            ->groupBy('item_id')
            ->get();

        $orderSlipItems = OrderSlipItem::whereHas('orderSlip', fn ($q) => $q->whereBetween('created_at', [$start, $end]))
            ->select('item_id', DB::raw('SUM(quantity) as total_quantity'), DB::raw('SUM(total_price) as total_price'))
            ->groupBy('item_id')
            ->get();

        $allItems = $orderItems->concat($orderSlipItems)->groupBy('item_id');
        $itemsSold = [];
        $totalItemRevenue = 0;

        foreach ($allItems as $itemId => $group) {
            $item = Item::find($itemId);
            $quantity = $group->sum('total_quantity');
            $totalPrice = $group->sum('total_price');
            $totalItemRevenue += $totalPrice;
        
            $itemsSold[] = [
                'image_url' => $item->image_url?? '', // Use um valor padrão se a imagem não existir, por exemplo, 'default.jpg
                'name' => $item->name,
                'quantity' => $quantity,
                'price' => number_format($totalPrice, 2, '.', ''),
                'stock' => $item->stock ?? 0, 
            ];
        }
        

        return [
            'store' => [
                    'orderslip_turnover' => number_format($orderslipTurnover, 2, '.', ''),
                    'orderslip' => $orderslipCount,
                ],
                'delivery' => [
                    'delivery_turnover' => number_format($deliveryTurnover, 2, '.', ''),
                    'delivery_orders' => $deliveryCount,
                ],
                'counter_sales' => [
                    'counter_sales' => number_format($counterTurnover, 2, '.', ''),
                    'counter_turnover' => $counterCount,
                ],
                'payment_method' => [
                    'payment_method_turnover' => array_map(fn ($v) => number_format($v, 2, '.', ''), $paymentMethodTurnover),
                    'payment_method_count' => $paymentMethodCount,
                ],
                'list_items_sold' => [
                    'list' => $itemsSold,
                    'list_items_sold_turnover' => number_format($totalItemRevenue, 2, '.', ''),
                ],
        ];
    }
}
