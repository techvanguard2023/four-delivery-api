<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeliveryAddress;
use Illuminate\Http\Request;

class DeliveryAddressController extends Controller
{
    public function index()
    {
        return DeliveryAddress::all();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip_code' => 'required|string|max:10'
        ]);

        $deliveryAddress = DeliveryAddress::create($validatedData);

        return response()->json($deliveryAddress, 201);
    }

    public function show(DeliveryAddress $deliveryAddress)
    {
        return $deliveryAddress;
    }

    public function update(Request $request, DeliveryAddress $deliveryAddress)
    {
        $validatedData = $request->validate([
            'customer_id' => 'sometimes|exists:customers,id',
            'address' => 'sometimes|string|max:255',
            'city' => 'sometimes|string|max:255',
            'state' => 'sometimes|string|max:255',
            'zip_code' => 'sometimes|string|max:10'
        ]);

        $deliveryAddress->update($validatedData);

        return response()->json($deliveryAddress, 200);
    }

    public function destroy(DeliveryAddress $deliveryAddress)
    {
        $deliveryAddress->delete();

        return response()->json(null, 204);
    }
}
