<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderSlip;
use App\Models\Stock;
use App\Models\Item;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Events\NewOrderSlipCreated;
use Illuminate\Support\Arr;

class OrderSlipController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $orderSlips = OrderSlip::query()
            ->where('company_id', $user->company_id)
            ->whereNotIn('status_id', [16, 9])
            ->with('orderSlipItems', 'user', 'status')
            ->get();

        $pendingCloseCount = OrderSlip::query()
            ->where('company_id', $user->company_id)
            ->where('status_id', 17)
            ->count();

        return response()->json([
            'orderSlips' => $orderSlips,
            'pendingCloseCount' => $pendingCloseCount
        ]);
    }



    public function store(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'customer_name' => 'nullable|string',
            'position' => 'string',
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
            'items.*.observation' => 'nullable|string',
        ]);

        $data['company_id'] = $user->company_id;
        $data['user_id'] = $user->id;

        $orderSlip = DB::transaction(function () use ($data, $request) {
            $totalPrice = 0;
            $orderItems = [];
        
            foreach ($request->items as $itemInput) {
                $item = Item::with('discounts')->findOrFail($itemInput['item_id']);
                $qty = $itemInput['quantity'];
        
                // Verifica se há desconto por quantidade
                $discount = $item->discounts
                    ->where('min_quantity', '<=', $qty)
                    ->sortByDesc('min_quantity')
                    ->first();
        
                $price = $discount ? $discount->discounted_price : $item->price;
        
                // Controle de estoque
                $stock = Stock::where('item_id', $item->id)->first();
        
                if (!$stock || $stock->quantity < $qty) {
                    throw new \Exception("Estoque insuficiente para o item ID: {$item->id}");
                }
        
                $stock->decrement('quantity', $qty);
        
                if ($stock->quantity <= 0) {
                    $item->update(['available' => false]);
                }
        
                // Soma ao total do pedido
                $totalPrice += $price * $qty;
        
                $orderItems[] = [
                    'item_id' => $item->id,
                    'quantity' => $qty,
                    'unit_price' => $price,
                    'total_price' => $price * $qty,
                    'observation' => $itemInput['observation'] ?? null,
                ];
            }
        
            $orderSlip = OrderSlip::create(array_merge($data, [
                'total_price' => $totalPrice,
            ]));
        
            $orderSlip->orderSlipItems()->createMany($orderItems);
        
            return $orderSlip;
        });
        

        
        event(new NewOrderSlipCreated($orderSlip));
        return response()->json($orderSlip->load('orderSlipItems'), 201);
    }



    public function show($id)
    {
        $orderSlip = OrderSlip::with(['status', 'orderSlipItems.item', 'user'])->findOrFail($id);
        return response()->json($orderSlip);
    }


    public function update(Request $request, $id)
    {
        $orderSlip = OrderSlip::with('orderSlipItems')->findOrFail($id);

        $data = $request->validate([
            'company_id' => 'sometimes|exists:companies,id',
            'customer_name' => 'nullable|string',
            'position' => 'sometimes|string',
            'status_id' => 'sometimes|exists:statuses,id',
            'discount' => 'sometimes|numeric|min:0',
            'payment_status' => 'sometimes|string',
            'last_status_id' => 'nullable|string',
            'last_payment_status' => 'nullable|string',
            'order_type_id' => 'sometimes|exists:order_types,id',
            'order_origin_id' => 'nullable|exists:order_origins,id',
            'items' => 'sometimes|array',
            'items.*.item_id' => 'required_with:items|exists:items,id',
            'items.*.quantity' => 'required_with:items|integer|min:1',
            'items.*.observation' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $orderSlip, $data) {

            // Atualiza dados básicos da comanda (exceto total_price e total_price_with_discount)
            $orderSlip->update(Arr::except($data, ['total_price', 'total_price_with_discount']));

            // Valida o desconto (não pode ser maior que o total_price)
            if (isset($data['discount']) && $data['discount'] > $orderSlip->total_price) {
                throw new \Exception("O valor do desconto não pode ser maior que o valor do total.");
            }

            if ($request->has('items')) {
                foreach ($request->items as $itemInput) {
                    $item = Item::with('discounts')->findOrFail($itemInput['item_id']);
                    $qty = $itemInput['quantity'];

                    $existingItem = $orderSlip->orderSlipItems()
                        ->where('item_id', $item->id)
                        ->first();

                    if ($existingItem) {
                        $newQty = $existingItem->quantity + $qty;
                        $additionalQty = $qty;
                    } else {
                        $newQty = $qty;
                        $additionalQty = $qty;
                    }

                    $stock = Stock::where('item_id', $item->id)->first();

                    if (!$stock || $stock->quantity < $additionalQty) {
                        throw new \Exception("Estoque insuficiente para o item ID: {$item->id}");
                    }

                    $stock->decrement('quantity', $additionalQty);

                    if ($stock->quantity <= 0) {
                        $item->update(['available' => false]);
                    }

                    $discount = $item->discounts
                        ->where('min_quantity', '<=', $newQty)
                        ->sortByDesc('min_quantity')
                        ->first();

                    $price = $discount ? $discount->discounted_price : $item->price;
                    $total = $newQty * $price;

                    if ($existingItem) {
                        $existingItem->update([
                            'quantity' => $newQty,
                            'unit_price' => $price,
                            'total_price' => $total,
                            'observation' => $itemInput['observation'] ?? $existingItem->observation,
                        ]);
                    } else {
                        $orderSlip->orderSlipItems()->create([
                            'item_id' => $item->id,
                            'quantity' => $qty,
                            'unit_price' => $price,
                            'total_price' => $price * $qty,
                            'observation' => $itemInput['observation'] ?? null,
                        ]);
                    }
                }

                // Recalcula total_price
                $orderSlip->total_price = $orderSlip->orderSlipItems()->sum('total_price');
            }

            // Atualiza o total_price_with_discount sempre que discount ou total_price mudar
            $discount = $data['discount'] ?? $orderSlip->discount ?? 0;
            $orderSlip->total_price_with_discount = max(0, $orderSlip->total_price - $discount);

            // Salva as alterações finais
            $orderSlip->save();
        });

        return response()->json($orderSlip->fresh('orderSlipItems'));
    }



    public function adjustOrRemoveItems(Request $request, $id)
    {
        try {
            $data = $request->validate([
                'items' => 'required|array',
                'items.*.order_slip_item_id' => 'required|integer|exists:order_slip_items,id',
                'items.*.action' => 'required|string|in:remove,adjust_quantity',
                'items.*.quantity' => 'required_if:items.*.action,adjust_quantity|integer|min:1',
            ]);

            DB::transaction(function () use ($id, $data) {
                $orderSlip = OrderSlip::with('orderSlipItems')->findOrFail($id);
                $newTotal = $orderSlip->total_price;

                foreach ($data['items'] as $input) {
                    $orderItem = $orderSlip->orderSlipItems()->findOrFail($input['order_slip_item_id']);

                    if ($input['action'] === 'remove') {
                        // Devolve estoque
                        $stock = Stock::where('item_id', $orderItem->item_id)->first();
                        if ($stock) {
                            $stock->increment('quantity', $orderItem->quantity);
                        }

                        $newTotal -= $orderItem->total_price;
                        $orderItem->delete();
                    }

                    if ($input['action'] === 'adjust_quantity') {
                        $removeQty = $input['quantity'];

                        if ($removeQty >= $orderItem->quantity) {
                            throw new \Exception("Quantidade a remover é maior ou igual à registrada.");
                        }

                        $unitPrice = $orderItem->unit_price;
                        $totalToRemove = $unitPrice * $removeQty;

                        $orderItem->quantity -= $removeQty;
                        $orderItem->total_price -= $totalToRemove;
                        $orderItem->save();

                        // Repor estoque
                        $stock = Stock::where('item_id', $orderItem->item_id)->first();
                        if ($stock) {
                            $stock->increment('quantity', $removeQty);
                        }

                        $newTotal -= $totalToRemove;
                    }
                }

                $orderSlip->update([
                    'total_price' => max($newTotal, 0),
                ]);
            });

            return response()->json(['message' => 'Itens atualizados com sucesso']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao ajustar/remover itens',
                'error' => $e->getMessage(),
            ], 400);
        }
    }




    public function destroy($id)
    {
        $orderSlip = OrderSlip::findOrFail($id);
        $orderSlip->delete();

        return response()->json(['message' => 'Order slip deleted successfully.']);
    }

    public function printView($id)
    {
        $orderSlip = OrderSlip::with('orderSlipItems')->findOrFail($id);
        return view('print.orderslip', compact('orderSlip'));
    }

    public function printCloseView($id)
    {
        $orderSlip = OrderSlip::with(['orderSlipItems.item', 'user', 'status', 'adjustments'])->findOrFail($id);
        return view('print.orderslip_close', compact('orderSlip'));
    }



    //Cálculo do total final (exemplo no controller ou service)

    // $total = $orderSlip->total_price;

    // foreach ($orderSlip->adjustments as $adj) {
    //     $adjustmentValue = $adj->value_type === 'percentage'
    //         ? ($total * ($adj->value / 100))
    //         : $adj->value;

    //     if ($adj->type === 'discount') {
    //         $total -= $adjustmentValue;
    //     } else {
    //         $total += $adjustmentValue;
    //     }
    // }
}