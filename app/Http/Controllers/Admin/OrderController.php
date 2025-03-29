<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Resources\ListOrdersResource;
use App\Services\UserRoleService;
use App\Http\Resources\ShowOrderResource;
use App\Models\Item;
use App\Models\OrderItem;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $userRoleId = UserRoleService::getUserRoleId($user);

        $query = Order::with(['customer', 'status', 'orderItems', 'payment', 'deliveryPerson'])
            ->where(function ($q) {
                $q->whereNull('location')
                    ->orWhere('location', '');
            });

        if ($userRoleId != 1) {
            $query->where('company_id', $user->company_id);
        }

        $query->orderBy('position', 'asc');

        $orders = $query->get();

        // Contagens por status_id (ajuste conforme seus IDs)
        $summary = [
            'received' => $orders->where('status_id', 3)->count(),
            'pending' => $orders->where('status_id', 13)->count(),
            'preparing' => $orders->where('status_id', 4)->count(),
            'waiting_for_delivery_man' => $orders->where('status_id', 5)->count(),
            'out_for_delivery' => $orders->where('status_id', 7)->count(),
            'delivered' => $orders->where('status_id', 8)->count(),
            'canceled' => $orders->where('status_id', 9)->count(),
        ];

        return ListOrdersResource::collection($orders)->additional([
            'summary' => $summary,
        ]);
    }





    public function orderByLocation(Request $request)
    {

        $user = $request->user();
        $roleId = UserRoleService::getUserRoleId($user); // Chama a função do serviço

        // Query base filtrando pedidos onde 'location' não é nulo nem vazio
        $query = Order::with(['customer', 'status', 'orderItems', 'payment', 'deliveryPerson'])
            ->whereNotNull('location')
            ->where('location', '!=', '');

        // Verifica o role do usuário para determinar se retorna todos os pedidos ou somente da empresa específica
        if ($roleId != 1) {
            $query->where('company_id', $user->company_id);
        }

        // Ordena pelos valores de 'location' e retorna a coleção paginada com 25 por página
        return ListOrdersResource::collection($query->orderBy('location')->paginate(25));
    }



    public function store(Request $request)
    {
        $user = $request->user(); // Obtém o usuário autenticado

        $validatedData = $request->validate([
            'customer_id' => 'required_if:order_type_id,1|exists:customers,id',
            'total_price' => 'required|numeric|min:0',
            'status_id' => 'required|exists:statuses,id',
            'payment_status' => 'required|string',
            'order_type_id' => 'required|exists:order_types,id',
            'location' => 'nullable|string',
            'order_origin_id' => 'nullable|exists:order_origins,id'
        ]);

        // Adiciona o company_id do usuário autenticado
        $validatedData['company_id'] = $user->company_id;

        // Usa uma transação para garantir integridade
        $order = DB::transaction(function () use ($validatedData, $request) {
            $order = Order::create($validatedData);

            // Adiciona os itens ao pedido
            if ($request->has('items')) {
                $orderItems = [];
                foreach ($request->items as $item) {
                    // Atualiza o estoque
                    $stock = Stock::where('item_id', $item['item_id'])->first();

                    if (!$stock || $stock->quantity < $item['quantity']) {
                        throw new \Exception("Estoque insuficiente para o item ID: {$item['item_id']}");
                    }

                    $stock->decrement('quantity', $item['quantity']);

                    // Adiciona o item ao pedido
                    $orderItems[] = [
                        'item_id' => $item['item_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'observation' => $item['observation'] ?? null,
                    ];
                }
                $order->orderItems()->createMany($orderItems);
            }

            return $order;
        });

        // Retorna o pedido e seus itens relacionados
        return response()->json($order->load('orderItems'), 201);
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
        if (in_array($validatedData['status_id'], [9, 10, 14])) {
            $order->payment_status = 'canceled';
        }

        if (in_array($validatedData['status_id'], [11])) {
            $order->payment_status = 'refunded';
        }

        if (in_array($validatedData['status_id'], [1, 3, 4, 5, 6, 7, 12, 13])) {
            $order->payment_status = 'pending';
        }

        if (in_array($validatedData['status_id'], [2, 8])) {
            $order->payment_status = 'paid';
        }

        // Salva a ordem com as alterações
        $order->save();

        return response()->json($order, 200);
    }

    public function updatePaymentStatus(Request $request, Order $order)
    {
        $validatedData = $request->validate([
            'payment_status' => 'required'
        ]);

        $order->payment_status = $validatedData['payment_status'];
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

    public function setDeliveryPerson(Request $request, Order $order)
    {
        $validatedData = $request->validate([
            'delivery_person_id' => 'required|exists:delivery_people,id'
        ]);

        $order->delivery_person_id = $validatedData['delivery_person_id'];
        $order->save();

        return response()->json($order, 200);
    }


    public function addItem(Request $request, Order $order)
    {
        // Validação dos dados recebidos
        $validatedData = $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'observation' => 'nullable|string'
        ]);

        // Busca o item pelo id
        $item = Item::find($validatedData['item_id']);

        // Busca o estoque do item
        $stock = Stock::where('item_id', $item->id)->first();

        // Verifica se a quantidade em estoque é suficiente
        if (!$stock || $stock->quantity < $validatedData['quantity']) {
            return response()->json([
                'error' => 'Quantidade insuficiente no estoque. Apenas ' . ($stock ? $stock->quantity : 0) . ' unidades disponíveis.'
            ], 400);
        }

        // Verifica se o item já existe no pedido
        $orderItem = $order->orderItems()->where('item_id', $item->id)->first();

        if ($orderItem) {
            // Se o item já existe no pedido, incrementa a quantidade
            $newQuantity = $orderItem->quantity + $validatedData['quantity'];

            // Verifica novamente se a quantidade total no pedido excede o estoque
            if ($newQuantity > $stock->quantity) {
                return response()->json([
                    'error' => 'Quantidade insuficiente no estoque. Apenas ' . $stock->quantity . ' unidades disponíveis.'
                ], 400);
            }

            // Atualiza a quantidade e a observação
            $orderItem->quantity = $newQuantity;
            if (!empty($validatedData['observation'])) {
                $orderItem->observation = $validatedData['observation'];
            }
        } else {
            // Se não existe, cria um novo registro de OrderItem
            $orderItem = new OrderItem([
                'item_id' => $item->id,
                'quantity' => $validatedData['quantity'],
                'price' => $item->price,
                'observation' => $validatedData['observation']
            ]);
        }

        // Salva o OrderItem (seja atualização ou criação)
        $order->orderItems()->save($orderItem);

        // Atualiza o estoque do item subtraindo a quantidade adicionada
        $stock->quantity -= $validatedData['quantity'];
        $stock->save();

        // Atualiza a coluna available na tabela items se o estoque chegar a 0
        if ($stock->quantity === 0) {
            $item->available = 0;
            $item->save();
        }

        // Recalcula o preço total do pedido
        $order->total_price = $order->orderItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });
        $order->save();

        return response()->json($orderItem, 201);
    }



    public function removeItem(Request $request, Order $order)
    {
        $validatedData = $request->validate([
            'item_id' => 'required|exists:items,id'
        ]);

        // Encontrar o item do pedido que será removido
        $orderItem = OrderItem::where('order_id', $order->id)
            ->where('item_id', $validatedData['item_id'])
            ->first();

        if ($orderItem) {
            // Buscar o estoque do item
            $stock = Stock::where('item_id', $orderItem->item_id)->first();

            if ($stock) {
                // Adicionar a quantidade do item removido de volta ao estoque
                $stock->quantity += $orderItem->quantity;

                // Atualizar a coluna available na tabela items se o estoque for maior que 0
                if ($stock->quantity > 0) {
                    $item = Item::find($orderItem->item_id);
                    $item->available = 1;
                    $item->save();
                }

                $stock->save();
            }

            // Excluir o item do pedido
            $orderItem->delete();

            // Recalcular o preço total do pedido
            $order->total_price = $order->orderItems->sum(function ($item) {
                return $item->price * $item->quantity;
            });
            $order->save();
        }

        return response()->json(null, 204);
    }



    public function updateItemQuantity(Request $request, Order $order)
    {
        $validatedData = $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $orderItem = OrderItem::where('order_id', $order->id)
            ->where('item_id', $validatedData['item_id'])
            ->first();

        if ($orderItem) {
            // Busca o estoque do item
            $stock = Stock::where('item_id', $orderItem->item_id)->first();

            if ($stock) {
                // Verifica se a nova quantidade é maior ou menor do que a quantidade atual
                if ($validatedData['quantity'] > $orderItem->quantity) {
                    // A quantidade está aumentando
                    $quantityToAdd = $validatedData['quantity'] - $orderItem->quantity;

                    // Verifica se há estoque suficiente
                    if ($stock->quantity < $quantityToAdd) {
                        return response()->json([
                            'error' => 'Quantidade insuficiente no estoque. Apenas ' . $stock->quantity . ' unidades disponíveis.'
                        ], 400);
                    }

                    // Atualiza o estoque
                    $stock->quantity -= $quantityToAdd;
                } elseif ($validatedData['quantity'] < $orderItem->quantity) {
                    // A quantidade está diminuindo
                    $quantityToAdd = $orderItem->quantity - $validatedData['quantity'];

                    // Adiciona a quantidade de volta ao estoque
                    $stock->quantity += $quantityToAdd;
                }

                // Salva a alteração no estoque
                $stock->save();

                // Atualiza a coluna available se o estoque estiver vazio
                $item = Item::find($orderItem->item_id);
                if ($stock->quantity <= 0) {
                    $item->available = 0;
                } else {
                    $item->available = 1;
                }
                $item->save();
            }

            // Atualiza a quantidade do item no pedido
            $orderItem->quantity = $validatedData['quantity'];
            $orderItem->save();

            // Recalcula o preço total do pedido
            $order->total_price = $order->orderItems->sum(function ($item) {
                return $item->price * $item->quantity;
            });
            $order->save();
        }

        return response()->json($orderItem, 200);
    }




    public function updateItem(Request $request, Order $order)
    {
        $validatedData = $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0'
        ]);

        $orderItem = OrderItem::where('order_id', $order->id)
            ->where('item_id', $validatedData['item_id'])
            ->first();

        if ($orderItem) {
            $orderItem->quantity = $validatedData['quantity'];
            $orderItem->price = $validatedData['price'];
            $orderItem->save();
        }

        return response()->json($orderItem, 200);
    }


    public function updateItemPrice(Request $request, Order $order)
    {
        $validatedData = $request->validate([
            'item_id' => 'required|exists:items,id',
            'price' => 'required|numeric|min:0'
        ]);

        $orderItem = OrderItem::where('order_id', $order->id)
            ->where('item_id', $validatedData['item_id'])
            ->first();

        if ($orderItem) {
            $orderItem->price = $validatedData['price'];
            $orderItem->save();
        }

        return response()->json($orderItem, 200);
    }

    public function updateOrderPositions(Request $request)
    {
        $orders = $request->input('orders');

        // Verifica se os pedidos foram fornecidos
        if (!is_array($orders) || empty($orders)) {
            return response()->json(['message' => 'Nenhum pedido fornecido!'], 400);
        }

        foreach ($orders as $orderData) {
            // Verifica se o ID do pedido está definido
            if (!isset($orderData['id'])) {
                return response()->json(['message' => 'ID do pedido não fornecido!'], 400);
            }

            $order = Order::find($orderData['id']); // Encontra o pedido pelo ID
            if ($order) {
                $order->position = $orderData['position']; // Atualiza a posição
                $order->save(); // Salva no banco de dados
            } else {
                return response()->json(['message' => 'Pedido não encontrado!'], 404);
            }
        }

        return response()->json(['message' => 'Posições atualizadas com sucesso!'], 200);
    }
}
