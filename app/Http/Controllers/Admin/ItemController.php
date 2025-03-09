<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use App\Http\Resources\ItemResource;
use App\Models\Stock;

use App\Services\UserRoleService;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $roleId = UserRoleService::getUserRoleId($user); // Chama a função do serviço

        if ($roleId == 1) {
            // Retorna todos os itens com paginação de 25 itens por página
            return ItemResource::collection(Item::with('stock', 'category')->paginate(15));
        } else {
            // Retorna itens da empresa específica com paginação de 25 itens por página
            return ItemResource::collection(Item::where('company_id', $user->company_id)->with('stock', 'category')->paginate(15));
        }
    }


    public function showByCategoryId(Request $request, $categoryId)
    {
        $user = $request->user();
        $roleId = UserRoleService::getUserRoleId($user); // Chama a função do serviço

        if ($roleId == 1) {
            return ItemResource::collection(Item::where('category_id', $categoryId)->get()->load('stock', 'category'));
        } else {
            return ItemResource::collection(Item::where('company_id', $user->company_id)->where('category_id', $categoryId)->get()->load('stock', 'category'));
        }
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_url' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'available' => 'required|boolean',
            'quantity' => 'required|integer|min:0' // Adicionado validação para quantidade
        ]);

        $validatedData['company_id'] = $user->company_id;

        $item = Item::create($validatedData);

        $stock = new Stock([
            'item_id' => $item->id,
            'quantity' => $validatedData['quantity'],
        ]);

        $item->stock()->save($stock);

        return response()->json($item->load('stock'), 201);
    }

    public function show(Item $item, Request $request)
    {
        $user = $request->user();

        // Garantir que o item pertence à mesma empresa do usuário autenticado
        if ($item->company_id !== $user->company_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return new ItemResource($item->load('stock', 'category'));
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
