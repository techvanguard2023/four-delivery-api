<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Services\UserRoleService;


class CategoryController extends Controller
{

    /**
     * @OA\Get(
     *     path="/categories",
     *     summary="Listar todas as categorias",
     *     tags={"Categorias"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de categorias",
     *         @OA\JsonContent(type="array", @OA\Items(type="object"))
     *     )
     * )
     */
    public function index()
    {
        return Category::orderBy('name', 'asc')->get();
    }

    /**
     * @OA\Get(
     *     path="/categories-with-total-items",
     *     summary="Listar categorias com total de itens e resumo de estoque",
     *     tags={"Categorias"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de categorias com detalhes",
     *         @OA\JsonContent(type="array", @OA\Items(type="object"))
     *     )
     * )
     */
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



    /**
     * @OA\Get(
     *     path="/categories-with-company-items",
     *     summary="Listar categorias com itens da empresa",
     *     tags={"Categorias"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de categorias e seus itens",
     *         @OA\JsonContent(type="array", @OA\Items(type="object"))
     *     )
     * )
     */
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




    /**
     * @OA\Post(
     *     path="/categories",
     *     summary="Criar nova categoria",
     *     tags={"Categorias"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"company_id","name","slug","description","image_url"},
     *             @OA\Property(property="company_id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Combos"),
     *             @OA\Property(property="slug", type="string", example="combos"),
     *             @OA\Property(property="description", type="string", example="Melhores combos da região"),
     *             @OA\Property(property="image_url", type="string", example="http://example.com/image.jpg")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Categoria criada",
     *         @OA\JsonContent(type="object")
     *     )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/categories/{id}",
     *     summary="Mostrar detalhes de uma categoria",
     *     tags={"Categorias"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da categoria",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalhes da categoria e itens paginados",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(response=404, description="Não encontrado")
     * )
     */
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


    /**
     * @OA\Put(
     *     path="/categories/{id}",
     *     summary="Atualizar uma categoria",
     *     tags={"Categorias"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="image_url", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Categoria atualizada",
     *         @OA\JsonContent(type="object")
     *     )
     * )
     */
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


    /**
     * @OA\Delete(
     *     path="/categories/{id}",
     *     summary="Excluir uma categoria",
     *     tags={"Categorias"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Excluído com sucesso")
     * )
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json(null, 204);
    }
}