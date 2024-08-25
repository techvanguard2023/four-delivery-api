<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeliveryPerson;
use Illuminate\Http\Request;

class DeliveryPersonController extends Controller
{
    public function index()
    {
        return DeliveryPerson::all();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string',
            'vehicle' => 'required|string'
        ]);

        $deliveryPerson = DeliveryPerson::create($validatedData);

        return response()->json($deliveryPerson, 201);
    }

    public function show(DeliveryPerson $deliveryPerson)
    {
        return $deliveryPerson;
    }

    public function update(Request $request, DeliveryPerson $deliveryPerson)
    {
        $validatedData = $request->validate([
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string',
            'vehicle' => 'sometimes|string'
        ]);

        $deliveryPerson->update($validatedData);

        return response()->json($deliveryPerson, 200);
    }

    public function destroy(DeliveryPerson $deliveryPerson)
    {
        $deliveryPerson->delete();

        return response()->json(null, 204);
    }
}
