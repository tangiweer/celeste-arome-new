<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wishlist;
use App\Models\Product;

class WishlistApiController extends Controller
{
    // Get wishlist
    public function index(Request $request)
    {
        $wishlist = $request->user()->wishlist()->with('product')->get();
        return response()->json($wishlist);
    }

    // Add product to wishlist
    public function add(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        $request->user()->wishlist()->firstOrCreate(['product_id' => $product->id]);
        return response()->json(['message' => 'Added to wishlist']);
    }

    // Remove product from wishlist
    public function remove(Request $request, $productId)
    {
        $request->user()->wishlist()->where('product_id', $productId)->delete();
        return response()->json(['message' => 'Removed from wishlist']);
    }
}
