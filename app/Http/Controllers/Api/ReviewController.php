<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    // Fetch all reviews
    public function index()
    {
        return response()->json(Review::latest()->get());
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string'
        ]);

        Review::create($validated);

        return response()->json(['message' => 'Review submitted successfully']);
    }
}

