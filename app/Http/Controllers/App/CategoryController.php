<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Services\UserRoleService;
use App\Models\Item;
use App\Models\Company;


class CategoryController extends Controller
{

    public function index()
    {
        return Category::orderBy('name', 'asc')->get();
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


    public function getCategoriesWithItemsByCompany($companyId, $slug)
    {
        // Verifica se a empresa existe e se o slug corresponde ao ID
        $company = Company::where('id', $companyId)
            ->where('slug', $slug)
            ->whereNull('deleted_at')
            ->first();

        if (!$company) {
            return response()->json([
                'message' => 'Empresa não encontrada ou dados inválidos.'
            ], 404);
        }

        // Busca apenas categorias que possuem pelo menos 1 item disponível e que deve ser exibido no menu
        $categories = Category::whereNull('deleted_at')
            ->whereHas('items', function ($query) use ($companyId) {
                $query->where('company_id', $companyId)
                    ->where('available', true)
                    ->where('show_in_menu', true)
                    ->whereNull('deleted_at');
            })
            ->with(['items' => function ($query) use ($companyId) {
                $query->where('company_id', $companyId)
                    ->where('available', true)
                    ->where('show_in_menu', true)
                    ->whereNull('deleted_at');
            }])
            ->orderBy('name')
            ->get();

        return response()->json($categories);
    }

}