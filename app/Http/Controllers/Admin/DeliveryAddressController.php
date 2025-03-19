<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeliveryAddress;
use Illuminate\Http\Request;

use App\Services\UserRoleService;


class DeliveryAddressController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $roleId = UserRoleService::getUserRoleId($user); // Chama a função do serviço

        if ($roleId == 1) {
            return DeliveryAddress::all();
        } else {
            return DeliveryAddress::where('company_id', $user->company_id)->get();
        }
    }

    public function store(Request $request)
    {
        $user = $request->user(); // Obtém o usuário autenticado
        $validatedData = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'address' => 'required|string|max:255',
            'number' => 'string|max:10',
            'complement' => 'string|max:255',
            'neighborhood' => 'required|string|max:255',
            'reference_point' => 'string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip_code' => 'required|string|max:10'
        ]);

        $validatedData['company_id'] = $user->company_id; // Adiciona o company_id do usuário autenticado

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
