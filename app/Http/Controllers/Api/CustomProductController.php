<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CustomProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomProductController extends Controller
{
    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'product_type' => 'required|string',
            'size' => 'nullable|string',
            'color' => 'nullable|string',
            'image_url' => 'nullable|image|max:2048', // Ensure proper file validation
            'notes' => 'nullable|string',
        ]);

        // Handle file upload
        $imagePath = null;
        if ($request->hasFile('image_url')) {
            $imagePath = $request->file('image_url')->store('uploads', 'public');
        }

        // Save data in database
        $product = CustomProduct::create([
            'name' => $request->name,
            'email' => $request->email,
            'product_type' => $request->product_type,
            'size' => $request->size,
            'color' => $request->color,
            'image_url' => $imagePath,
            'notes' => $request->notes,
        ]);

        return response()->json(['message' => 'Product request submitted successfully!', 'data' => $product], 201);
    }
}

