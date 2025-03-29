<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderSlip;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class OrderSlipController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Busca todos os OrderSlips da empresa do usuário autenticado
        $orderSlips = OrderSlip::query()
            ->where('company_id', $user->company_id)
            ->with('orderSlipItems') // Carrega os itens do pedido
            ->get(); // Executa a query

        return response()->json($orderSlips);
    }


    public function store(Request $request)
    {
        $user = $request->user(); // Usuário autenticado

        $data = $request->validate([
            'customer_name' => 'nullable|string',
            'position' => 'required|string',
            'total_price' => 'required|numeric',
            'status_id' => 'required|exists:statuses,id',
            'payment_status' => 'required|string',
            'last_status_id' => 'nullable|string',
            'last_payment_status' => 'nullable|string',
            'order_type_id' => 'required|exists:order_types,id',
            'order_origin_id' => 'nullable|exists:order_origins,id',
            'duration' => 'nullable|date_format:H:i',
            'items' => 'array|required',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.observation' => 'nullable|string',
        ]);

        $data['company_id'] = $user->company_id;
        $data['user_id'] = $user->id;

        $orderSlip = DB::transaction(function () use ($data, $request) {
            $orderSlip = OrderSlip::create($data);

            // Processa os itens
            $orderItems = [];
            foreach ($request->items as $item) {
                $stock = Stock::where('item_id', $item['item_id'])->first();

                if (!$stock || $stock->quantity < $item['quantity']) {
                    throw new \Exception("Estoque insuficiente para o item ID: {$item['item_id']}");
                }

                $stock->decrement('quantity', $item['quantity']);

                $orderItems[] = [
                    'item_id' => $item['item_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'observation' => $item['observation'] ?? null,
                ];
            }

            $orderSlip->orderSlipItems()->createMany($orderItems);

            return $orderSlip;
        });

        return response()->json($orderSlip->load('orderSlipItems'), 201);
    }


    public function show($id)
    {
        $orderSlip = OrderSlip::with(['company', 'status', 'orderType', 'orderOrigin'])->findOrFail($id);
        return response()->json($orderSlip);
    }

    public function update(Request $request, $id)
    {
        $orderSlip = OrderSlip::findOrFail($id);

        $data = $request->validate([
            'company_id' => 'sometimes|exists:companies,id',
            'customer_name' => 'nullable|string',
            'position' => 'sometimes|string',
            'total_price' => 'sometimes|numeric',
            'status_id' => 'sometimes|exists:statuses,id',
            'payment_status' => 'sometimes|string',
            'last_status_id' => 'nullable|string',
            'last_payment_status' => 'nullable|string',
            'order_type_id' => 'sometimes|exists:order_types,id',
            'order_origin_id' => 'nullable|exists:order_origins,id',
        ]);

        $orderSlip->update($data);

        return response()->json($orderSlip);
    }

    public function destroy($id)
    {
        $orderSlip = OrderSlip::findOrFail($id);
        $orderSlip->delete();

        return response()->json(['message' => 'Order slip deleted successfully.']);
    }
}
