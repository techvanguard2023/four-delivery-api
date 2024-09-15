<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeliveryPerson;
use Illuminate\Http\Request;

use App\Services\UserRoleService;

class DeliveryPersonController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $roleId = UserRoleService::getUserRoleId($user); // Chama a função do serviço

        if ($roleId == 1) {
            return DeliveryPerson::paginate(25);
        } else {
            return DeliveryPerson::where('company_id', $user->company_id)->paginate(25);
        }
    }

    public function store(Request $request)
    {
        $user = $request->user(); // Obtém o usuário autenticado
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string',
            'vehicle' => 'required|string'
        ]);

        $validatedData['company_id'] = $user->company_id; // Adiciona o company_id do usuário autenticado

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
