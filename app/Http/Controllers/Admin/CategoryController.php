<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;


class CategoryController extends Controller
{


    public function index()
    {
        return Category::all();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'company_id' => 'required|interger',
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'required|string',
            'image_url' => 'required|string'
        ]);

        $category = Category::create($validatedData);

        return response()->json($category, 201);
    }

    public function show(Category $category)
    {
        $category = Category::find($category->id);

        if (!$category) {
            return response()->json(['message' => 'Categoria não encontrado.'], 404);
        }

        $category->load('items');

        return response()->json($category);
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
