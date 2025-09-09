<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ProductApiController extends Controller
{
    // Public: List active products
    public function index()
    {
        $products = Product::where('is_active', true)->get()->map(function ($p) {
            $p->image_url = $p->image ? asset('storage/' . $p->image) : null;
            return $p;
        });

        return response()->json(['products' => $products], 200);
    }

    // Public: Show single product
    public function show($id)
    {
        $product = Product::find($id);
        if (!$product) return response()->json(['message' => 'Product not found'], 404);

        $product->image_url = $product->image ? asset('storage/' . $product->image) : null;
        return response()->json($product, 200);
    }

    // Admin: Create product
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $product = Product::create($validated);
        $product->image_url = $product->image ? asset('storage/' . $product->image) : null;

        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product
        ], 201);
    }

    // Admin: Update product
    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) return response()->json(['message' => 'Product not found'], 404);

        $validated = $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'name' => 'sometimes|required|string|max:255',
            'brand' => 'sometimes|nullable|string|max:255',
            'description' => 'sometimes|nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'stock' => 'sometimes|required|integer|min:0',
            'is_active' => 'sometimes|boolean',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if (isset($validated['name'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $product->update($validated);

        return response()->json([
            'message' => 'Product updated successfully',
            'product' => $product
        ], 200);
    }

    // Admin: Delete product
    public function destroy($id)
    {
        $product = Product::find($id);
        if (!$product) return response()->json(['message' => 'Product not found'], 404);

        $product->delete();
        return response()->json(['message' => 'Product deleted successfully'], 200);
    }

    // ✅ Checkout page: fetch cart from DB
    public function checkoutPage()
    {
        $user = Auth::user();
        $cart = $user->cart()->with('items.product')->first();  // get the user's cart

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json([
                'cart'  => [],
                'total' => 0
            ]);
        }

        $cartItems = $cart->items->map(function ($item) {
            return [
                'product_id' => $item->product->id,
                'name'       => $item->product->name,
                'qty'        => $item->qty,
                'price'      => $item->unit_price,
                'image_url'  => $item->product->image ? asset('storage/' . $item->product->image) : null,
            ];
        });

        $total = $cartItems->sum(fn($item) => $item['qty'] * $item['price']);

        return response()->json([
            'cart'  => $cartItems,
            'total' => $total,
        ]);
    }

    // You can add a payment processing method if needed
    public function process(Request $request)
    {
        // Payment logic here (Stripe, PayPal, etc.)
        return response()->json(['message' => 'Payment processed successfully']);
    }
}
