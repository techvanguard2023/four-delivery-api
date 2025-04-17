<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use App\Http\Resources\ItemResource;
use App\Models\Stock;
use Illuminate\Support\Str;

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

    public function getOnlyAvailableItems(Request $request, $categoryId)
    {
        $user = $request->user();
        $roleId = UserRoleService::getUserRoleId($user); // Chama a função do serviço

        if ($roleId == 1) {
            return ItemResource::collection(Item::where('category_id', $categoryId)->where('available', true)->get()->load('stock', 'category'));
        } else {
            return ItemResource::collection(Item::where('company_id', $user->company_id)->where('category_id', $categoryId)->where('available', true)->get()->load('stock', 'category'));
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
            'quantity' => 'required|integer|min:0'
        ]);

        $validatedData['company_id'] = $user->company_id;

        // Criando o slug único
        $slug = Str::slug($validatedData['name']) . '-' . time();
        $validatedData['slug'] = $slug;

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
        // Verifica se o usuário tem permissão para atualizar o item
        if ($request->user()->company_id !== $item->company_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validatedData = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'image_url' => 'nullable|string',
            'price' => 'sometimes|numeric|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'available' => 'sometimes|boolean',
            'quantity' => 'sometimes|integer|min:0'
        ]);

        // Atualiza o slug se o nome for alterado
        if (isset($validatedData['name'])) {
            $validatedData['slug'] = Str::slug($validatedData['name']) . '-' . time();
        }

        $item->update($validatedData);

        // Atualiza a quantidade do estoque, se fornecida
        if (isset($validatedData['quantity'])) {
            if ($item->stock) {
                $item->stock->update(['quantity' => $validatedData['quantity']]);
            } else {
                // Caso não tenha stock ainda (raro, mas possível)
                $item->stock()->create(['quantity' => $validatedData['quantity']]);
            }
        }

        return response()->json($item->load('stock'), 200);
    }


    public function destroy(Request $request, Item $item)
    {
        // Verifica se o usuário tem permissão para deletar o item
        if ($request->user()->company_id !== $item->company_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Deleta o estoque associado (se houver)
        if ($item->stock) {
            $item->stock->delete();
        }

        // Deleta o item
        $item->delete();

        return response()->json(['message' => 'Item deleted successfully'], 200);
    }


    public function updateAvailable(Request $request, Item $item)
    {
        $request->validate([
            'available' => 'required|boolean',
        ]);

        $item->available = $request->available;
        $item->save();

        return response()->json([
            'message' => 'Disponibilidade atualizada com sucesso.',
            'item' => $item
        ]);
    }

    public function updateShowInMenu(Request $request, Item $item)
    {
        $request->validate([
            'show_in_menu' => 'required|boolean',
        ]);

        $item->show_in_menu = $request->show_in_menu;
        $item->save();

        return response()->json([
            'message' => 'Visibilidade no menu atualizada com sucesso.',
            'item' => $item
        ]);
    }

}
