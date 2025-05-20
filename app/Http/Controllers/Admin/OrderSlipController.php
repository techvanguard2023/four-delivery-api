<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\OrderSlip;
use App\Models\Stock;
use App\Models\Item;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Events\NewOrderSlipCreated;
use Illuminate\Support\Arr;
use App\Models\Company;
use App\Models\OrderSlipItem;

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
            'items.*.is_complimentary' => 'boolean',
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
                $isComplimentary = $itemInput['is_complimentary'] ?? false;

                // Controle de estoque
                $stock = Stock::where('item_id', $item->id)->first();

                if (!$stock || $stock->quantity < $qty) {
                    throw new \Exception("Estoque insuficiente para o item ID: {$item->id}");
                }

                $stock->decrement('quantity', $qty);

                if ($stock->quantity <= 0) {
                    $item->update(['available' => false]);
                }

                if ($isComplimentary) {
                    // Brinde: valores zerados, mas ainda decrementa estoque
                    $orderItems[] = [
                        'item_id' => $item->id,
                        'quantity' => $qty,
                        'unit_price' => 0,
                        'total_price' => 0,
                        'observation' => $itemInput['observation'] ?? null,
                        'is_complimentary' => true,
                    ];
                    continue;
                }

                // Caso normal: aplica desconto se houver
                $unitPrice = $item->price;
                $totalItemPrice = 0;

                $discount = $item->discounts
                    ->sortByDesc('min_quantity')
                    ->first();

                if ($discount) {
                    $groupSize = $discount->min_quantity;
                    $discountedUnits = intdiv($qty, $groupSize) * $groupSize;
                    $fullPriceUnits = $qty - $discountedUnits;

                    $totalItemPrice = ($discountedUnits * $discount->discounted_price) + ($fullPriceUnits * $item->price);
                    $unitPrice = $totalItemPrice / $qty;
                } else {
                    $totalItemPrice = $item->price * $qty;
                }

                $totalPrice += $totalItemPrice;

                $orderItems[] = [
                    'item_id' => $item->id,
                    'quantity' => $qty,
                    'unit_price' => round($unitPrice, 2),
                    'total_price' => round($totalItemPrice, 2),
                    'observation' => $itemInput['observation'] ?? null,
                    'is_complimentary' => false,
                ];
            }

            $orderSlip = OrderSlip::create(array_merge($data, [
                'total_price' => round($totalPrice, 2),
            ]));

            $orderSlip->orderSlipItems()->createMany($orderItems);

            return $orderSlip;
        });

        event(new NewOrderSlipCreated($orderSlip));

        return response()->json($orderSlip->load('orderSlipItems'), 201);
    }





    public function show(Request $request, $id)
    {
        $user = $request->user();

        $orderSlip = OrderSlip::with(['status', 'orderSlipItems.item', 'user', 'payments.paymentMethod'])
            ->where('company_id', $user->company_id)
            ->findOrFail($id);
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
            'items.*.is_complimentary' => 'boolean',
            'items.*.observation' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $orderSlip, $data) {

            $orderSlip->update(Arr::except($data, ['total_price', 'total_price_with_discount']));

            if (isset($data['discount']) && $data['discount'] > $orderSlip->total_price) {
                throw new \Exception("O valor do desconto não pode ser maior que o valor do total.");
            }

            if ($request->has('items')) {
                foreach ($request->items as $itemInput) {
                    $item = Item::with('discounts')->findOrFail($itemInput['item_id']);
                    $qty = $itemInput['quantity'];
                    $isComplimentary = $itemInput['is_complimentary'] ?? false;

                    // Controle de estoque
                    $stock = Stock::where('item_id', $item->id)->first();
                    if (!$stock || $stock->quantity < $qty) {
                        throw new \Exception("Estoque insuficiente para o item ID: {$item->id}");
                    }

                    $stock->decrement('quantity', $qty);

                    if ($stock->quantity <= 0) {
                        $item->update(['available' => false]);
                    }

                    if ($isComplimentary) {
                        // Brinde: não junta com existentes, cria novo com valor zerado
                        $orderSlip->orderSlipItems()->create([
                            'item_id' => $item->id,
                            'quantity' => $qty,
                            'unit_price' => 0,
                            'total_price' => 0,
                            'observation' => $itemInput['observation'] ?? null,
                            'is_complimentary' => true,
                        ]);
                        continue;
                    }

                    // Itens normais: soma quantidade se já existir
                    $existingItem = $orderSlip->orderSlipItems()
                        ->where('item_id', $item->id)
                        ->where(function ($q) {
                            $q->whereNull('is_complimentary')->orWhere('is_complimentary', false);
                        })
                        ->first();

                    if ($existingItem) {
                        $newQty = $existingItem->quantity + $qty;
                        $additionalQty = $qty;
                    } else {
                        $newQty = $qty;
                        $additionalQty = $qty;
                    }

                    // Aplica desconto se houver
                    $discount = $item->discounts
                        ->where('min_quantity', '<=', $newQty)
                        ->sortByDesc('min_quantity')
                        ->first();

                    if ($discount) {
                        $groupSize = $discount->min_quantity;
                        $discountedUnits = intdiv($newQty, $groupSize) * $groupSize;
                        $fullPriceUnits = $newQty - $discountedUnits;

                        $total = ($discountedUnits * $discount->discounted_price) + ($fullPriceUnits * $item->price);
                        $unitPrice = $total / $newQty;
                    } else {
                        $unitPrice = $item->price;
                        $total = $newQty * $unitPrice;
                    }

                    if ($existingItem) {
                        $existingItem->update([
                            'quantity' => $newQty,
                            'unit_price' => round($unitPrice, 2),
                            'total_price' => round($total, 2),
                            'observation' => $itemInput['observation'] ?? $existingItem->observation,
                        ]);
                    } else {
                        $orderSlip->orderSlipItems()->create([
                            'item_id' => $item->id,
                            'quantity' => $qty,
                            'unit_price' => round($unitPrice, 2),
                            'total_price' => round($unitPrice * $qty, 2),
                            'observation' => $itemInput['observation'] ?? null,
                            'is_complimentary' => false,
                        ]);
                    }
                }

                $orderSlip->total_price = $orderSlip->orderSlipItems()
                    ->where(function ($q) {
                        $q->whereNull('is_complimentary')->orWhere('is_complimentary', false);
                    })
                    ->sum('total_price');
            }

            $discount = $data['discount'] ?? $orderSlip->discount ?? 0;
            $orderSlip->total_price_with_discount = max(0, $orderSlip->total_price - $discount);

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
                    'total_price_with_discount' => max($newTotal - ($orderSlip->discount ?? 0), 0),
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

    public function publicView($companyId, $slug, Request $request)
    {

        // Verifica se a empresa existe e se o slug corresponde ao ID
        $company = Company::where('id', $companyId)
            ->where('slug', $slug)
            ->whereNull('deleted_at')
            ->first();

        if (!$company) {
            return response()->json([
                'message' => 'Empresa não encontrada ou dados inválidos.'
            ], 404);
        }

        $customerName = $request->query('name');
        $position = $request->query('position');

        $orderSlip = OrderSlip::with(['status', 'orderSlipItems.item', 'user', 'payments.paymentMethod'])
            ->where('status_id', OrderStatus::OPEN_ORDER_SLIP)
            ->where('customer_name', $customerName)
            ->where('position', $position)
            ->where('company_id', $companyId)
            ->firstOrFail();

        return response()->json($orderSlip);
    }
}
