<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

use App\Services\UserRoleService;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $roleId = UserRoleService::getUserRoleId($user); // Chama a função do serviço

        if ($roleId == 1) {
            return Order::with(['customer', 'status', 'orderItems'])->get();
        } else {
            return Order::where('company_id', $user->company_id)->with(['customer', 'status', 'orderItems'])->get();
        }
    }

    public function store(Request $request)
    {
        $user = $request->user(); // Obtém o usuário autenticado
        $validatedData = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'total_price' => 'required|numeric|min:0',
            'status_id' => 'required|exists:statuses,id',
            'payment_status' => 'required|string'
        ]);

        $validatedData['company_id'] = $user->company_id; // Adiciona o company_id do usuário autenticado

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
