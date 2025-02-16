<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlist = Wishlist::where('user_id', Auth::id())->with('product')->get();
        return response()->json($wishlist);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $wishlist = Wishlist::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id
        ]);

        return response()->json(['message' => 'Added to wishlist', 'wishlist' => $wishlist]);
    }

    public function destroy($id)
    {
        $wishlist = Wishlist::where('user_id', Auth::id())->where('id', $id)->first();

        if (!$wishlist) {
            return response()->json(['error' => 'Item not found'], 404);
        }

        $wishlist->delete();
        return response()->json(['message' => 'Removed from wishlist']);
    }
}

