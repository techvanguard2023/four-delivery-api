<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Resources\ListOrdersResource;
use App\Services\UserRoleService;
use App\Http\Resources\ShowOrderResource;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $roleId = UserRoleService::getUserRoleId($user); // Chama a função do serviço

        if ($roleId == 1) {
            return ListOrdersResource::collection(Order::with(['customer', 'status', 'orderItems', 'payment', 'deliveryPerson'])->get());
        } else {
            return ListOrdersResource::collection(Order::where('company_id', $user->company_id)->with(['customer', 'status', 'orderItems', 'payment', 'deliveryPerson'])->get());
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

    public function show(Request $request, Order $order)
    {
        $user = $request->user();
        $roleId = UserRoleService::getUserRoleId($user); // Chama a função do serviço

        if ($roleId == 1) {
            // Retorna um único recurso, não uma coleção
            return new ShowOrderResource($order->load(['customer', 'orderItems', 'customer.deliveryAddresses']));
        } else {
            $order = Order::where('company_id', $user->company_id)
                ->with(['customer', 'orderItems'])
                ->find($order->id); // Corrigido para usar o ID do pedido

            if ($order) {
                return new ShowOrderResource($order);
            } else {
                return response()->json(['error' => 'Order not found'], 404);
            }
        }
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



    public function updateStatus(Request $request, Order $order)
    {
        $validatedData = $request->validate([
            'status_id' => 'required|exists:statuses,id'
        ]);

        // Armazena os valores antigos
        $order->last_status_id = $order->status_id;
        $order->last_payment_status = $order->payment_status;

        // Atualiza o status_id com o novo valor
        $order->status_id = $validatedData['status_id'];

        // Verifica os status de cancelamento
        if (in_array($validatedData['status_id'], [10, 11, 12, 15, 17])) {
            $order->payment_status = 'canceled';
        }

        // Verifica os status de pagamento concluído
        if (in_array($validatedData['status_id'], [2, 9])) {
            $order->payment_status = 'paid';
        }

        // Salva a ordem com as alterações
        $order->save();

        return response()->json($order, 200);
    }

    public function getLastStatusAndReativate(Request $request)
    {
        $validatedData = $request->validate([
            'order_id' => 'required|exists:orders,id'
        ]);

        $order = Order::find($validatedData['order_id']);
        $order->status_id = $order->last_status_id;
        $order->payment_status = $order->last_payment_status;
        $order->save();

        return response()->json($order, 200);
    }
}
