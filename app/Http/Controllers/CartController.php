<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /** Show the user's cart */
    public function index()
    {
        $cart = Auth::user()->cart()->firstOrCreate();   // fixed: firstOrCreate
        $cart->load(['items.product.primaryImage']);

        return view('cart.index', compact('cart'));
    }

    /** Add product to cart (qty +1) */
    public function add(Product $product)
    {
        if (!$product->is_active || $product->stock < 1) {
            return back()->with('error', 'This product is unavailable.');
        }

        $user = Auth::user();

        try {
            DB::transaction(function () use ($user, $product) {
                $locked = DB::table('products')->where('id', $product->id)->lockForUpdate()->first();
                if (!$locked || !$locked->is_active || $locked->stock < 1) {
                    throw new \RuntimeException('Not enough stock for this product.');
                }

                $cart = $user->cart()->firstOrCreate();

                $item   = $cart->items()->firstOrNew(['product_id' => $product->id]);
                $newQty = ($item->exists ? $item->qty : 0) + 1;

                if ($newQty > $locked->stock) {
                    throw new \RuntimeException('Not enough stock for this product.');
                }

                $item->qty        = $newQty;
                $item->unit_price = $product->price; // snapshot price
                $item->save();
            });
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Added to cart.');
    }

    /** Update quantity */
    public function updateQty(Request $request, CartItem $item)
    {
        $request->validate(['qty' => ['required', 'integer', 'min:1']]);

        if ($item->cart->user_id !== Auth::id()) {
            abort(403);
        }

        try {
            DB::transaction(function () use ($item, $request) {
                $locked = DB::table('products')->where('id', $item->product_id)->lockForUpdate()->first();
                if (!$locked || !$locked->is_active) {
                    throw new \RuntimeException('This product is no longer available.');
                }
                if ((int)$request->qty > $locked->stock) {
                    throw new \RuntimeException('Requested quantity exceeds available stock.');
                }

                $item->update(['qty' => (int)$request->qty]);
            });
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Cart updated.');
    }

    /** Remove item */
    public function remove(CartItem $item)
    {
        if ($item->cart->user_id !== Auth::id()) {
            abort(403);
        }

        $item->delete();

        return back()->with('success', 'Item removed from cart.');
    }
}
