<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return Order::with(['customer', 'status', 'orderItems'])->get();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'total_price' => 'required|numeric|min:0',
            'status_id' => 'required|exists:statuses,id',
            'payment_status' => 'required|string'
        ]);

        $order = Order::create($validatedData);

        return response()->json($order, 201);
    }

    public function show(Order $order)
    {
        return $order->load(['customer', 'status', 'orderItems']);
    }

    public function update(Request $request, Order $order)
    {
        $validatedData = $request->validate([
            'customer_id' => 'sometimes|exists:customers,id',
            'total_price' => 'sometimes|numeric|min:0',
            'status_id' => 'sometimes|exists:statuses,id',
            'payment_status' => 'sometimes|string'
        ]);

        $order->update($validatedData);

        return response()->json($order, 200);
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return response()->json(null, 204);
    }
}
