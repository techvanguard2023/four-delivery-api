<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Services\UserRoleService;


class CategoryController extends Controller
{


    public function index()
    {
        return Category::orderBy('name', 'asc')->get();
    }

    public function listCategoriesWithTotalItems(Request $request)
    {
        $user = $request->user();

        $categories = Category::withCount(['items' => function ($query) use ($user) {
            $query->where('company_id', $user->company_id);
        }])
            ->with([
                'items' => function ($query) use ($user) {
                    $query->where('company_id', $user->company_id)
                        ->with('stock')
                        ->orderBy('name', 'asc');
                }
            ])
            ->orderBy('name', 'asc')
            ->get()
            ->map(function ($category) {
                $items = $category->items;

                $maxStockItem = $items->sortByDesc(fn($item) => $item->stock->quantity ?? 0)->first();
                $minStockItem = $items->sortBy(fn($item) => $item->stock->quantity ?? 0)->first();

                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'description' => $category->description,
                    'image_url' => $category->image_url,
                    'total_items' => $category->items_count,
                    'max_stock_item' => $maxStockItem ? [
                        'id' => $maxStockItem->id,
                        'name' => $maxStockItem->name,
                        'stock' => $maxStockItem->stock->quantity ?? 0
                    ] : null,
                    'min_stock_item' => $minStockItem ? [
                        'id' => $minStockItem->id,
                        'name' => $minStockItem->name,
                        'stock' => $minStockItem->stock->quantity ?? 0
                    ] : null,
                ];
            });

        return response()->json($categories);
    }



    public function listCategoriesWithCompanyItems(Request $request)
    {
        $user = $request->user();
        $roleId = UserRoleService::getUserRoleId($user);

        $categories = Category::with(['items' => function ($query) use ($user, $roleId) {
            if ($roleId != 1) {
                $query->where('company_id', $user->company_id);
            }
        }])->orderBy('name', 'asc')->get();

        return response()->json($categories);
    }




    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'company_id' => 'required|interger',
            'name' => 'required|string|max:255|unique:categories,name',
            'slug' => 'required|string|unique:categories',
            'description' => 'required|string',
            'image_url' => 'required|string'
        ]);

        $category = Category::create($validatedData);

        return response()->json($category, 201);
    }

    public function show(Category $category, Request $request)
    {
        $user = $request->user();

        $category = Category::find($category->id);

        if (!$category) {
            return response()->json(['message' => 'Categoria não encontrada.'], 404);
        }

        // Paginação de itens relacionados
        $itemsPerPage = 25; // Defina o número de itens por página
        $items = $category->items()->where('company_id', $user->company_id)->paginate($itemsPerPage);

        return response()->json([
            'category' => $category,
            'items' => $items, // Inclui a paginação
        ]);
    }


    public function update(Request $request, Category $category)
    {
        $validatedData = $request->validate([
            'name' => 'sometimes|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'sometimes|string',
            'image_url' => 'sometimes|string'
        ]);

        // Filtra os dados validados para remover quaisquer valores nulos
        $filteredData = array_filter($validatedData, function ($value) {
            return !is_null($value);
        });

        $category->update($filteredData);

        return response()->json($category, 200);
    }


    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json(null, 204);
    }
}