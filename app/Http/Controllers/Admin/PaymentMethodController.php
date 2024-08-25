<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function index()
    {
        return PaymentMethod::all();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:payment_methods,name'
        ]);

        $paymentMethod = PaymentMethod::create($validatedData);

        return response()->json($paymentMethod, 201);
    }

    public function show(PaymentMethod $paymentMethod)
    {
        return $paymentMethod;
    }

    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $validatedData = $request->validate([
            'name' => 'sometimes|string|max:255|unique:payment_methods,name,' . $paymentMethod->id
        ]);

        $paymentMethod->update($validatedData);

        return response()->json($paymentMethod, 200);
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        $paymentMethod->delete();

        return response()->json(null, 204);
    }
}
