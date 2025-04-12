<?php

namespace App\Http\Controllers\App;

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