<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartApiController extends Controller
{
    // Show current user's cart
    public function index()
    {
        $cart = Auth::user()->cart()->firstOrCreate();
        $cart->load(['items.product']);

        return response()->json($cart);
    }

    // Add product to cart
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);
        $qty = $request->qty ?? 1;

        if (!$product->is_active || $product->stock < $qty) {
            return response()->json(['error' => 'Product unavailable or insufficient stock'], 400);
        }

        $cart = Auth::user()->cart()->firstOrCreate();
        $item = $cart->items()->firstOrNew(['product_id' => $product->id]);
        $item->qty = ($item->exists ? $item->qty : 0) + $qty;
        $item->unit_price = $product->price;
        $item->save();

        return response()->json(['message' => 'Product added to cart', 'item' => $item]);
    }

    // Update quantity of cart item
    public function update(Request $request, CartItem $item)
    {
        $this->authorize('update', $item);

        $request->validate(['qty' => 'required|integer|min:1']);

        $product = $item->product;
        if ($request->qty > $product->stock) {
            return response()->json(['error' => 'Insufficient stock'], 400);
        }

        $item->qty = $request->qty;
        $item->save();

        return response()->json(['message' => 'Cart item updated', 'item' => $item]);
    }

    // Remove item from cart
    public function destroy(CartItem $item)
    {
        $this->authorize('delete', $item);
        $item->delete();

        return response()->json(['message' => 'Item removed from cart']);
    }

    // Clear entire cart
    public function clear()
    {
        $cart = Auth::user()->cart()->firstOrCreate();
        $cart->items()->delete();

        return response()->json(['message' => 'Cart cleared']);
    }
}
