<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use App\Http\Resources\ItemResource;

use App\Services\UserRoleService;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $roleId = UserRoleService::getUserRoleId($user); // Chama a função do serviço

        if ($roleId == 1) {
            return ItemResource::collection(Item::all()->load('stock', 'category'));
        } else {
            return ItemResource::collection(Item::where('company_id', $user->company_id)->get()->load('stock', 'category'));
        }
    }

    public function store(Request $request)
    {
        $user = $request->user(); // Obtém o usuário autenticado
        $validatedData = $request->validate([
            // 'company_id' => 'required|interger', // Remove a validação de company_id
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_url' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'available' => 'required|boolean'
        ]);

        $validatedData['company_id'] = $user->company_id; // Adiciona o company_id do usuário autenticado

        $item = Item::create($validatedData);

        return response()->json($item, 201);
    }

    public function show(Item $item)
    {
        return $item;
    }

    public function update(Request $request, Item $item)
    {
        $validatedData = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|numeric|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'available' => 'sometimes|boolean'
        ]);

        $item->update($validatedData);

        return response()->json($item, 200);
    }

    public function destroy(Item $item)
    {
        $item->delete();

        return response()->json(null, 204);
    }
}
