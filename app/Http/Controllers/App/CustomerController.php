<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Item;
use App\Models\Order;

class CustomerController extends Controller
{

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'is_whatsapp' => 'boolean',
            'address' => 'required|string',
            'number' => 'required|string',
            'complement' => 'nullable|string',
            'neighborhood' => 'required|string',
            'reference_point' => 'nullable|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'zip_code' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.observation' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            // 1. Buscar ou criar o cliente
            $customer = Customer::firstOrCreate(
                ['phone' => $validated['phone']],
                [
                    'company_id' => $validated['company_id'],
                    'name' => $validated['name'],
                    'is_whatsapp' => $validated['is_whatsapp'] ?? true,
                ]
            );

            // 2. Criar o endereço
            $address = $customer->deliveryAddresses()->create([
                'address' => $validated['address'],
                'number' => $validated['number'],
                'complement' => $validated['complement'] ?? null,
                'neighborhood' => $validated['neighborhood'],
                'reference_point' => $validated['reference_point'] ?? null,
                'city' => $validated['city'],
                'state' => $validated['state'],
                'zip_code' => $validated['zip_code'],
            ]);

            // 3. Calcular total
            $total = 0;
            $items = [];

            foreach ($validated['items'] as $itemData) {
                $item = Item::findOrFail($itemData['item_id']);
                $price = $item->price; // ajuste se tiver preço promocional
                $subtotal = $price * $itemData['quantity'];

                $items[] = [
                    'item_id' => $item->id,
                    'quantity' => $itemData['quantity'],
                    'price' => $price,
                    'observation' => $itemData['observation'] ?? null,
                ];

                $total += $subtotal;
            }

            // 4. Criar o pedido
            $order = Order::create([
                'company_id' => $validated['company_id'],
                'customer_id' => $customer->id,
                'total_price' => $total,
                'discount' => 0,
                'total_price_with_discount' => $total,
                'status_id' => 1, // ajuste conforme status inicial
                'payment_status' => 'pending',
                'order_type_id' => 1, // ajuste conforme necessário (ex: delivery)
                'order_origin_id' => null,
                'location' => $address->address . ', ' . $address->number,
                'position' => null,
            ]);

            // 5. Criar os itens do pedido
            foreach ($items as $item) {
                $order->orderItems()->create($item);
            }

            DB::commit();

            return response()->json([
                'message' => 'Pedido realizado com sucesso!',
                'order_id' => $order->id
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Erro ao processar pedido.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
