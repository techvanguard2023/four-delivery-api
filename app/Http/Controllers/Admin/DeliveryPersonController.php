<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeliveryPerson;
use Illuminate\Http\Request;

use App\Services\UserRoleService;

class DeliveryPersonController extends Controller
{
    /**
     * @OA\Get(
     *     path="/delivery-people",
     *     summary="Listar entregadores da empresa (paginado)",
     *     tags={"Entregadores"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Lista paginada de entregadores")
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/delivery-people",
     *     summary="Cadastrar novo entregador",
     *     tags={"Entregadores"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","phone","vehicle"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="phone", type="string"),
     *             @OA\Property(property="vehicle", type="string"),
     *             @OA\Property(property="is_whatsapp", type="boolean")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Entregador cadastrado")
     * )
     */
    public function store(Request $request)
    {
        $user = $request->user(); // Obtém o usuário autenticado
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string',
            'is_whatsapp' => 'boolean',
            'vehicle' => 'required|string'
        ]);

        $validatedData['company_id'] = $user->company_id; // Adiciona o company_id do usuário autenticado

        $deliveryPerson = DeliveryPerson::create($validatedData);

        return response()->json($deliveryPerson, 201);
    }

    /**
     * @OA\Get(
     *     path="/delivery-people/{id}",
     *     summary="Mostrar detalhes de um entregador",
     *     tags={"Entregadores"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Detalhes do entregador")
     * )
     */
    public function show(DeliveryPerson $deliveryPerson)
    {
        return $deliveryPerson;
    }

    /**
     * @OA\Put(
     *     path="/delivery-people/{id}",
     *     summary="Atualizar um entregador",
     *     tags={"Entregadores"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Entregador atualizado")
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/delivery-people/{id}",
     *     summary="Excluir um entregador",
     *     tags={"Entregadores"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Entregador excluído")
     * )
     */
    public function destroy(DeliveryPerson $deliveryPerson)
    {
        $deliveryPerson->delete();

        return response()->json(null, 204);
    }
}
