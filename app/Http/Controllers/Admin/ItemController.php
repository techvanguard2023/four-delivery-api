<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use App\Http\Resources\ItemResource;
use App\Models\Stock;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


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

    public function allItemsWithoutPaginate(Request $request)
    {
        $user = $request->user();

        return ItemResource::collection(
            Item::where('company_id', $user->company_id)
                ->where('available', 1)
                ->with(['stock', 'category'])
                ->get()
        );
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

        // Converte campos monetários para o formato correto (ponto decimal)
        $request->merge([
            'original_price' => $request->original_price ? str_replace(',', '.', $request->original_price) : null,
            'price' => str_replace(',', '.', $request->price),
        ]);

        // Converte os preços dentro do array de descontos (se houver)
        if ($request->has('discounts')) {
            $discounts = collect($request->discounts)->map(function ($discount) {
                $discount['discounted_price'] = str_replace(',', '.', $discount['discounted_price']);
                return $discount;
            });

            $request->merge(['discounts' => $discounts->toArray()]);
        }


        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_url' => 'nullable|string',
        
            // Valores monetários — aceitam ponto como separador decimal
            'original_price' => ['nullable', 'numeric', 'min:0'],
            'price' => ['required', 'numeric', 'min:0'],
        
            'category_id' => 'nullable|exists:categories,id',
            'available' => 'required|boolean',
            'show_in_menu' => 'required|boolean',
            'quantity' => 'required|integer|min:0',
        
            // Descontos
            'discounts' => 'nullable|array',
            'discounts.*.min_quantity' => 'required|integer|min:1',
            'discounts.*.discounted_price' => ['required', 'numeric', 'min:0'],
        ]);
        
        
        

        return DB::transaction(function () use ($validatedData, $user) {
            $validatedData['company_id'] = $user->company_id;

            // Cria o slug único
            $slug = Str::slug($validatedData['name']) . '-' . time();
            $validatedData['slug'] = $slug;

            // Cria o item
            $item = Item::create($validatedData);

            // Cria o estoque
            $item->stock()->create([
                'quantity' => $validatedData['quantity'],
            ]);

            // Cria os descontos, se houver
            if (!empty($validatedData['discounts'])) {
                foreach ($validatedData['discounts'] as $discount) {
                    $item->discounts()->create($discount);
                }
            }

            return response()->json($item->load(['stock', 'discounts']), 201);
        });
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
        // Verifica se o usuário pertence à mesma empresa do item
        if ($request->user()->company_id !== $item->company_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Validação dos dados recebidos
        $validatedData = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'image_url' => 'nullable|url',
            'original_price' => 'sometimes|numeric|min:0',
            'price' => 'sometimes|numeric|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'available' => 'sometimes|boolean',
            'show_in_menu' => 'sometimes|boolean',
            'quantity' => 'sometimes|integer|min:0',
            'discounts' => 'nullable|array',
            'discounts.*.min_quantity' => 'required_with:discounts|integer|min:1',
            'discounts.*.discounted_price' => 'required_with:discounts|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Atualiza o slug se o nome for alterado
            if (isset($validatedData['name']) && $validatedData['name'] !== $item->name) {
                $validatedData['slug'] = Str::slug($validatedData['name']) . '-' . uniqid();
            }

            // Atualiza o item
            $item->update($validatedData);

            // Atualiza a quantidade do estoque
            if (isset($validatedData['quantity'])) {
                $item->stock()->updateOrCreate([], [
                    'quantity' => $validatedData['quantity']
                ]);
            }

            // Atualiza os descontos
            if (isset($validatedData['discounts'])) {
                $item->discounts()->delete(); // Remove os antigos
                $item->discounts()->createMany($validatedData['discounts']); // Adiciona os novos
            }

            DB::commit();

            // Retorna o item atualizado com os relacionamentos
            return response()->json($item->load(['stock', 'discounts']), 200);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao atualizar item: ' . $e->getMessage());

            return response()->json(['error' => 'Erro interno ao atualizar item'], 500);
        }
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
