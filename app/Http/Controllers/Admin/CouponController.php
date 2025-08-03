<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Coupon;

class CouponController extends Controller
{

    public function index()
    {
        return Coupon::all();
    }

    public function show($id)
    {
        return Coupon::findOrFail($id);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'code' => 'required|string|unique:coupons,code',
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date',
        ]);

        $coupon = Coupon::create($data);

        return response()->json($coupon, 201);
    }

    public function update(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);

        $data = $request->validate([
            'code' => 'sometimes|string|unique:coupons,code,' . $coupon->id,
            'type' => 'sometimes|in:fixed,percentage',
            'value' => 'sometimes|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date',
        ]);

        $coupon->update($data);

        return response()->json($coupon);
    }

    public function deactivate($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->active = false;
        $coupon->save();

        return response()->json(['message' => 'Cupom desativado.']);
    }

    public function activate($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->active = true;
        $coupon->save();

        return response()->json(['message' => 'Cupom ativado.']);
    }

    public function destroy($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();

        return response()->json(['message' => 'Cupom excluído.']);
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'type' => 'required|in:order,order_slip',
            'id' => 'required|integer',
        ]);

        $coupon = Coupon::where('code', $request->code)->first();

        if (!$coupon || !$coupon->isValid()) {
            return response()->json(['message' => 'Cupom inválido ou expirado'], 422);
        }

        $model = $request->type === 'order' ? \App\Models\Order::class : \App\Models\OrderSlip::class;
        $order = $model::findOrFail($request->id);

        $newTotal = $coupon->applyDiscount($order->total_price);
        $discountValue = $order->total_price - $newTotal;

        if ($order->couponUsage) {
            return response()->json(['message' => 'Este pedido já tem um cupom aplicado.'], 400);
        }


        // Aplicar no pedido
        $order->update([
            'discount' => $discountValue,
            'total_price_with_discount' => $newTotal,
        ]);

        // Associar cupom ao pedido
        $order->couponUsage()->create([
            'coupon_id' => $coupon->id,
        ]);

        // Atualiza uso do cupom
        $coupon->increment('used');

        return response()->json([
            'discount' => $discountValue,
            'new_total' => $newTotal,
        ]);
    }

}
