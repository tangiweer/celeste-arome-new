<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Wishlist; 
class WishlistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /** Legacy entry point → redirect into My Account hub */
    public function index()
    {
        return redirect()->route('account.favorites');
    }

    /** Add a product to wishlist */
    public function add(Request $request, $productId)
    {
        $user = Auth::user();
        $product = Product::findOrFail($productId);

        $query = method_exists($user, 'wishlist')
            ? $user->wishlist()->where('product_id', $productId)
            : Wishlist::where('user_id', $user->id)->where('product_id', $productId);

        $existing = $query->first();

        if (!$existing) {
            if (method_exists($user, 'wishlist')) {
                $user->wishlist()->create(['product_id' => $productId]);
            } else {
                Wishlist::create(['user_id' => $user->id, 'product_id' => $productId]);
            }
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => !$existing,
                'message' => $existing ? 'Item already in wishlist!' : 'Item added to wishlist!',
            ]);
        }

        return redirect()->route('account.favorites')
            ->with('success', $existing ? 'Item already in wishlist.' : 'Item added to wishlist.');
    }

    /** Remove a product from wishlist */
    public function remove(Request $request, $productId)
    {
        $user = Auth::user();

        if (method_exists($user, 'wishlist')) {
            $user->wishlist()->where('product_id', $productId)->delete();
        } else {
            Wishlist::where('user_id', $user->id)->where('product_id', $productId)->delete();
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Item removed from wishlist']);
        }

        return redirect()->route('account.favorites')->with('success', 'Item removed from wishlist!');
    }

    /** Toggle wishlist state */
    public function toggle(Request $request, $productId)
    {
        $user = Auth::user();

        $query = method_exists($user, 'wishlist')
            ? $user->wishlist()->where('product_id', $productId)
            : Wishlist::where('user_id', $user->id)->where('product_id', $productId);

        $existing = $query->first();

        if ($existing) {
            $existing->delete();
            $payload = ['success' => true, 'in_wishlist' => false, 'message' => 'Removed from wishlist'];
        } else {
            if (method_exists($user, 'wishlist')) {
                $user->wishlist()->create(['product_id' => $productId]);
            } else {
                Wishlist::create(['user_id' => $user->id, 'product_id' => $productId]);
            }
            $payload = ['success' => true, 'in_wishlist' => true, 'message' => 'Added to wishlist'];
        }

        if ($request->wantsJson()) {
            return response()->json($payload);
        }

        return redirect()->route('account.favorites')->with('success', $payload['message']);
    }

    /** NEW: Move ONE wishlist product to cart, then remove it from wishlist */
    public function moveToCart(Request $request, $productId)
    {
        $user = Auth::user();
        $product = Product::findOrFail($productId);

        DB::transaction(function () use ($user, $product) {
            // Ensure cart exists
            $cart = $user->cart()->firstOrCreate([]);

            // Add or increment cart item
            $item = $cart->items()->where('product_id', $product->id)->first();
            if ($item) {
                $item->qty += 1;
                $item->save();
            } else {
                $cart->items()->create([
                    'product_id' => $product->id,
                    'qty'        => 1,
                    'unit_price' => $product->price ?? 0, // adjust if you have sale_price
                ]);
            }

            // Remove from wishlist
            if (method_exists($user, 'wishlist')) {
                $user->wishlist()->where('product_id', $product->id)->delete();
            } else {
                Wishlist::where('user_id', $user->id)->where('product_id', $product->id)->delete();
            }
        });

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Item moved to cart']);
        }

        return redirect()->route('account.favorites')->with('success', 'Item moved to cart.');
    }

    /** NEW: Move ALL wishlist products to cart, then clear wishlist */
    public function moveAllToCart(Request $request)
    {
        $user = Auth::user();

        // Load all wishlist items with products
        $items = method_exists($user, 'wishlist')
            ? $user->wishlist()->with('product')->get()
            : Wishlist::with('product')->where('user_id', $user->id)->get();

        if ($items->isEmpty()) {
            return redirect()->route('account.favorites')->with('error', 'Your wishlist is empty.');
        }

        DB::transaction(function () use ($user, $items) {
            $cart = $user->cart()->firstOrCreate([]);

            foreach ($items as $w) {
                $p = $w->product;
                if (!$p) { continue; }

                $existing = $cart->items()->where('product_id', $p->id)->first();
                if ($existing) {
                    $existing->qty += 1;
                    $existing->save();
                } else {
                    $cart->items()->create([
                        'product_id' => $p->id,
                        'qty'        => 1,
                        'unit_price' => $p->price ?? 0,
                    ]);
                }
            }

            // Clear wishlist
            if (method_exists($user, 'wishlist')) {
                $user->wishlist()->delete();
            } else {
                Wishlist::where('user_id', $user->id)->delete();
            }
        });

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'All wishlist items added to cart']);
        }

        return redirect()->route('account.favorites')->with('success', 'All wishlist items added to cart.');
    }
}
