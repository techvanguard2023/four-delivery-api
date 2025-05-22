<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalesReportController extends Controller
{
    public function getItemSales(Request $request)
    {
        $request->validate([
            'category_id' => 'required|integer|exists:categories,id',
            'item_id' => 'required|integer|exists:items,id',
            'date' => 'required|date',
        ]);

        $user = $request->user();
        $companyId = $user->company_id;
        $categoryId = $request->category_id;
        $itemId = $request->item_id;
        $date = $request->date;

        $startDate = Carbon::parse($date)->startOfDay();
        $endDate = Carbon::parse($date)->endOfDay();

        // Query para somar vendas e buscar nome, categoria e estoque
        $result = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('items', 'order_items.item_id', '=', 'items.id')
            ->join('categories', 'items.category_id', '=', 'categories.id')
            ->leftJoin('stocks', 'items.id', '=', 'stocks.item_id')
            ->where('items.category_id', $categoryId)
            ->where('order_items.item_id', $itemId)
            ->where('orders.company_id', $companyId)
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select(
                'items.name as item_name',
                'categories.name as category_name',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.price * order_items.quantity) as total_value'),
                'stocks.quantity as stock_quantity'
            )
            ->groupBy('items.id', 'categories.id', 'stocks.quantity')
            ->first();

        // Mesmo para order_slip_items
        $result_slip = DB::table('order_slip_items')
            ->join('order_slips', 'order_slip_items.order_slip_id', '=', 'order_slips.id')
            ->join('items', 'order_slip_items.item_id', '=', 'items.id')
            ->join('categories', 'items.category_id', '=', 'categories.id')
            ->leftJoin('stocks', 'items.id', '=', 'stocks.item_id')
            ->where('items.category_id', $categoryId)
            ->where('order_slip_items.item_id', $itemId)
            ->where('order_slips.company_id', $companyId)
            ->whereBetween('order_slips.created_at', [$startDate, $endDate])
            ->select(
                'items.name as item_name',
                'categories.name as category_name',
                DB::raw('SUM(order_slip_items.quantity) as total_quantity'),
                DB::raw('SUM(order_slip_items.unit_price * order_slip_items.quantity) as total_value'),
                'stocks.quantity as stock_quantity'
            )
            ->groupBy('items.id', 'categories.id', 'stocks.quantity')
            ->first();

        // Somar as quantidades e valores de ambas as tabelas
        $totalQuantity = ($result->total_quantity ?? 0) + ($result_slip->total_quantity ?? 0);
        $totalValue = ($result->total_value ?? 0) + ($result_slip->total_value ?? 0);
        $itemName = $result->item_name ?? ($result_slip->item_name ?? '');
        $categoryName = $result->category_name ?? ($result_slip->category_name ?? '');
        $stockQuantity = $result->stock_quantity ?? $result_slip->stock_quantity ?? 0;

        return response()->json([
            'item_name' => $itemName,
            'category_name' => $categoryName,
            'total_quantity' => $totalQuantity,
            'total_value' => number_format($totalValue, 2, '.', ''),
            'stock_quantity' => $stockQuantity,
            'date' => $date,
        ]);
    }

    public function getSalesReport(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
        ]);

        $user = $request->user();
        $companyId = $user->company_id;
        $date = $request->date;

        $startDate = Carbon::parse($date)->startOfDay();
        $endDate = Carbon::parse($date)->endOfDay();

        // Total de vendas e quantidade do Delivery (orders)
        $delivery = DB::table('orders')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.company_id', $companyId)
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->selectRaw('
                SUM(order_items.price * order_items.quantity) as total_value,
                SUM(order_items.quantity) as total_quantity
            ')
            ->first();

        // Comanda: order_slips com position preenchida
        $comanda = DB::table('order_slips')
            ->join('order_slip_items', 'order_slips.id', '=', 'order_slip_items.order_slip_id')
            ->where('order_slips.company_id', $companyId)
            ->where('order_slips.position', '!=', 'counter-sale')
            ->whereBetween('order_slips.created_at', [$startDate, $endDate])
            ->selectRaw('
                SUM(order_slip_items.unit_price * order_slip_items.quantity) as total_value,
                SUM(order_slip_items.quantity) as total_quantity
            ')
            ->first();

        // Balcão: order_slips com position nula
        $balcao = DB::table('order_slips')
            ->join('order_slip_items', 'order_slips.id', '=', 'order_slip_items.order_slip_id')
            ->where('order_slips.company_id', $companyId)
            ->where('order_slips.position', 'counter-sale')
            ->whereBetween('order_slips.created_at', [$startDate, $endDate])
            ->selectRaw('
                SUM(order_slip_items.unit_price * order_slip_items.quantity) as total_value,
                SUM(order_slip_items.quantity) as total_quantity
            ')
            ->first();

        return response()->json([
            'date' => $date,
            'delivery' => [
                'total_value' => $delivery->total_value ?? 0,
                'total_quantity' => $delivery->total_quantity ?? 0,
            ],
            'comanda' => [
                'total_value' => $comanda->total_value ?? 0,
                'total_quantity' => $comanda->total_quantity ?? 0,
            ],
            'balcao' => [
                'total_value' => $balcao->total_value ?? 0,
                'total_quantity' => $balcao->total_quantity ?? 0,
            ],
        ]);
    }
}
