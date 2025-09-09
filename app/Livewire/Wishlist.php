<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;


class Wishlist extends Component 
{
    public $wishlist;

    public function mount()
    {
        $this->loadWishlist();
    }

    private function user()
    {
        return Auth::user();
    }

    public function loadWishlist(): void
    {
        $user = $this->user();
        $this->wishlist = $user
            ? $user->wishlist()
                  ->with('product.primaryImage')
                  ->get()
            : collect();
    }

    /** Move ONE wishlist row to cart, then remove it from wishlist */
    public function addToCart($itemId)
    {
        $user = $this->user();
        if (!$user) return redirect()->route('login');

        $wish = $user->wishlist()
            ->with('product')
            ->find($itemId);

        if (!$wish || !$wish->product) return;

        DB::transaction(function () use ($user, $wish) {
            $cart = $user->cart()->firstOrCreate([]);

            // merge quantity if already there
            $item = $cart->items()->firstOrNew(['product_id' => $wish->product_id]);
            $item->qty        = ($item->exists ? $item->qty : 0) + 1;
            $item->unit_price = $wish->product->price;
            $item->save();

            // remove from wishlist
            $wish->delete();
        });

        $this->loadWishlist();
        session()->flash('success', 'Item added to cart.');
    }

    /** Move ALL wishlist items to cart, then clear wishlist */
    public function addAllToCart()
    {
        $user = $this->user();
        if (!$user) return redirect()->route('login');

        $items = $user->wishlist()->with('product')->get();
        if ($items->isEmpty()) {
            session()->flash('message', 'Your wishlist is empty.');
            return;
        }

        DB::transaction(function () use ($user, $items) {
            $cart = $user->cart()->firstOrCreate([]);

            foreach ($items as $wish) {
                $product = $wish->product;
                if (!$product) continue;
                if (isset($product->is_active) && !$product->is_active) continue;
                if (isset($product->stock) && $product->stock <= 0) continue;

                $item = $cart->items()->firstOrNew(['product_id' => $product->id]);
                $item->qty        = ($item->exists ? $item->qty : 0) + 1;
                $item->unit_price = $product->price;
                $item->save();
            }

            // clear wishlist after moving all
            $user->wishlist()->delete();
        });

        $this->loadWishlist();
        session()->flash('success', 'All wishlist items were added to your cart and the wishlist was cleared.');
        // If you prefer to navigate to the cart:
        // return redirect()->route('cart.index');
    }

    public function remove($itemId)
    {
        $user = $this->user();
        if ($user) {
            $wishlistItem = $user->wishlist()->find($itemId);
            if ($wishlistItem) {
                $wishlistItem->delete();
                $this->loadWishlist();
                session()->flash('message', 'Item removed from wishlist!');
            }
        }
    }

    public function render() 
    { 
        return view('livewire.wishlist'); 
    }
}
