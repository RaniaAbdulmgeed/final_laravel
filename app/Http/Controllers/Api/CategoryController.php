<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Get all categories where state = 1.
     */
    public function index()
    {
        $categories = Category::where('state', 1)->get();
        return CategoryResource::collection($categories);
    }

    /**
     * Get a single category by ID.
     */
    public function show($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        return new CategoryResource($category);
    }

    /**
     * Insert a new category.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'state' => 'required|boolean',
        ]);

        $category = Category::create([
            'name' => $request->name,
            'description' => $request->description,
            'state' => $request->state,
        ]);

        return response()->json([
            'message' => 'Category created successfully',
            'category' => new CategoryResource($category)
        ], 201);
    }

    /**
     * Update a category by ID.
     */
    public function update(Request $request, $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'state' => 'required|boolean',
        ]);

        $category->update($request->all());

        return response()->json([
            'message' => 'Category updated successfully',
            'category' => new CategoryResource($category)
        ]);
    }

/**
 * Soft delete a category by ID, but prevent deletion if it has products.
 */
public function destroy($id)
{
    $category = Category::find($id);

    if (!$category) {
        return response()->json(['message' => 'Category not found'], 404);
    }

    // Check if the category has any associated products
    if ($category->products()->exists()) {
        return response()->json([
            'message' => 'Cannot delete this category because it has associated products.'
        ], 400);
    }

    // If no products are associated, proceed with deletion
    $category->delete();

    return response()->json(['message' => 'Category deleted successfully']);
}

}
