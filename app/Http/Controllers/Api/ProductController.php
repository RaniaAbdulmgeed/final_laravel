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
            return response()->json(['message' => 'Product not found'], 404);
        }

        return new ProductResource($product);
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
            'image_url' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048', // Image validation
            'state' => 'required|integer|in:0,1'
        ]);

        // Convert JSON strings to arrays
        $validatedData['size_options'] = json_decode($validatedData['size_options'], true) ?? [];
        $validatedData['color_options'] = json_decode($validatedData['color_options'], true) ?? [];

        // Handle image upload
        if ($request->hasFile('image_url')) {
            $path = $request->file('image_url')->store('products', 'public'); // Save in storage/app/public/products
            $validatedData['image_url'] = asset("storage/{$path}"); // Get full URL
        }

        // Create product
        $product = Product::create($validatedData);

        return response()->json([
            'message' => 'Product created successfully',
            'product' => new ProductResource($product)
        ], 201);
    }

    /**
     * Update a product by ID.
     */
    /**
 * Update a product by ID using PATCH (Partial Update).
 */
public function update(Request $request, $id)
{
    $product = Product::find($id);

    if (!$product) {
        return response()->json(['message' => 'Product not found'], 404);
    }

    $validatedData = $request->validate([
        'name' => 'sometimes|string|max:255',
        'description' => 'sometimes|nullable|string',
        'category_id' => 'sometimes|exists:categories,id',
        'price' => 'sometimes|numeric|min:0',
        'discount' => 'sometimes|nullable|numeric|min:0',
        'stock_quantity' => 'sometimes|integer|min:0',
        'size_options' => 'sometimes|nullable|string',
        'color_options' => 'sometimes|nullable|string',
        'image_url' => 'sometimes|nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        'state' => 'sometimes|integer|in:0,1'
    ]);

    // Assign values manually
    if ($request->has('name')) {
        $product->name = $request->name;
    }
    if ($request->has('description')) {
        $product->description = $request->description;
    }
    if ($request->has('category_id')) {
        $product->category_id = $request->category_id;
    }
    if ($request->has('price')) {
        $product->price = $request->price;
    }
    if ($request->has('discount')) {
        $product->discount = $request->discount;
    }
    if ($request->has('stock_quantity')) {
        $product->stock_quantity = $request->stock_quantity;
    }
    if ($request->has('size_options')) {
        $product->size_options = json_decode($request->size_options, true) ?? [];
    }
    if ($request->has('color_options')) {
        $product->color_options = json_decode($request->color_options, true) ?? [];
    }
    if ($request->has('image_url')) {
        $path = $request->file('image_url')->store('products', 'public');
        $product->image_url = asset("storage/{$path}");
    }
    if ($request->has('state')) {
        $product->state = $request->state;
    }

    $product->save();

    return response()->json([
        'message' => 'Product updated successfully',
        'product' => new ProductResource($product->refresh())
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
