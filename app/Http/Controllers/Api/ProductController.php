<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\Category;
class ProductController extends Controller
{
    /**
     * Get all products where state = 1.
     */
    public function index()
    {
        $products = Product::where('state', 1)->get();
        return ProductResource::collection($products);
    }

    /**
     * Get a single product by ID.
     */
    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        return response()->json([
            'product' => $product
        ]);
    }


    /**
     * Insert a new product (using form data).
     */
    public function store(Request $request)
{
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'category_id' => 'required|exists:categories,id',
        'price' => 'required|numeric',
        'discount' => 'nullable|numeric',
        'stock_quantity' => 'required|integer',
        'size_options' => 'nullable|string',
        'color_options' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048', // ✅ Accept "image" instead of "image_url"
        'state' => 'required|integer|in:0,1'
    ]);

    // 🔥 Handle image upload properly
    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('products', 'public'); // ✅ Store in "storage/app/public/products"
        $validatedData['image_url'] = asset("storage/{$path}"); // ✅ Convert to URL for frontend
    }

    $validatedData['size_options'] = json_decode($request->size_options, true) ?? [];
    $validatedData['color_options'] = json_decode($request->color_options, true) ?? [];

    $product = Product::create($validatedData);

    return response()->json([
        'message' => 'Product created successfully',
        'product' => new ProductResource($product)
    ], 201);
}

    /**
 * Update a product by ID using PATCH (Partial Update).
 */
/**
 * Update a product by ID using PATCH (Partial Update).
 */
public function update(Request $request, $id)
{
    // Find the product, including soft-deleted ones if needed
    $product = Product::withTrashed()->find($id);

    if (!$product) {
        return response()->json(['message' => 'Product not found'], 404);
    }

    // Validate request data
    $validatedData = $request->validate([
        'name' => 'string|max:255',
        'description' => 'nullable|string',
        'category_id' => 'exists:categories,id',
        'price' => 'numeric|min:0',
        'discount' => 'numeric|min:0|max:100',
        'stock_quantity' => 'integer|min:0',
        'size_options' => 'nullable|json', // JSON format expected
        'color_options' => 'nullable|json', // JSON format expected
        'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Ensure it's an image
        'state' => 'boolean'
    ]);

    // Handle file upload if provided
    if ($request->hasFile('image_url')) {
        $file = $request->file('image_url');
        $filePath = $file->store('products', 'public'); // Store in `storage/app/public/products`
        $validatedData['image_url'] = $filePath;
    }

    // Convert JSON string to array if necessary
    if ($request->has('size_options')) {
        $validatedData['size_options'] = json_decode($request->input('size_options'), true);
    }
    if ($request->has('color_options')) {
        $validatedData['color_options'] = json_decode($request->input('color_options'), true);
    }

    // Update product
    $product->update($validatedData);

    return response()->json([
        'message' => 'Product updated successfully',
        'product' => $product
    ], 200);
}


        /**
     * Get all products by category ID where state = 1.
     */
    public function getByCategory($categoryId)
    {
        $products = Product::where('category_id', $categoryId)
                            ->where('state', 1)
                            ->get();

        if ($products->isEmpty()) {
            return response()->json(['message' => 'No products found in this category'], 404);
        }

        return ProductResource::collection($products);
    }

    /**
     * Soft delete a product by ID.
     */
    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }
}
