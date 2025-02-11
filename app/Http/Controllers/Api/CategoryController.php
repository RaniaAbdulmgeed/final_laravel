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

    // Log incoming request to debug
    \Log::info('Updating category ID: ' . $id, [
        'Raw Request Data' => $request->all(), // Log the full request
        'Existing Data' => $category->toArray()
    ]);

    // Validate only the provided fields (PATCH allows partial updates)
    $validatedData = $request->validate([
        'name' => 'sometimes|required|string|max:255',
        'description' => 'sometimes|nullable|string',
        'state' => 'sometimes|required|boolean',
    ]);

    // Force update by setting attributes explicitly
    $category->fill($validatedData);
    $updated = $category->save();

    if (!$updated) {
        return response()->json([
            'message' => 'Update failed. No changes detected or database issue.',
            'category' => new CategoryResource($category)
        ], 500);
    }

    return response()->json([
        'message' => 'Category updated successfully',
        'category' => new CategoryResource($category->refresh()) // Ensures updated data is returned
    ]);
}

/**
 * Soft delete a category by ID, but prevent deletion if it has products.
 */
public function destroy($id)
{
    try {
        $category = Category::find($id);

        // Check if category exists
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        // Check if the category has associated products
        if ($category->products()->exists()) {
            return response()->json([
                'message' => 'Cannot delete this category because it has associated products.'
            ], 400);
        }

        // Soft delete the category
        $category->delete();

        return response()->json(['message' => 'Category deleted successfully'], 200);

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'An error occurred while deleting the category.',
            'error' => $e->getMessage()
        ], 500);
    }
}


}
