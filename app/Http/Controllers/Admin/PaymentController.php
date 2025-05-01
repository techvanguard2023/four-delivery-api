<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        return Payment::all();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'order_slip_id' => 'required|exists:order_slips,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,completed,cancelled'
        ]);

        $payment = Payment::create($validatedData);

        return response()->json($payment, 201);
    }

    public function show(Payment $payment)
    {
        return $payment;
    }

    public function update(Request $request, Payment $payment)
    {
        $validatedData = $request->validate([
            'order_slip_id' => 'sometimes|exists:order_slips,id',
            'payment_method_id' => 'sometimes|exists:payment_methods,id',
            'amount' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|in:pending,completed,cancelled'
        ]);

        $payment->update($validatedData);

        return response()->json($payment, 200);
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();

        return response()->json(null, 204);
    }
}
