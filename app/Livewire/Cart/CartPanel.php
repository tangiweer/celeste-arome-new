<?php

namespace App\Livewire\Cart;

use Livewire\Component;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use App\Models\CartItem;

class CartPanel extends Component
{
    public $cartItems = [];

    public function mount()
    {
        $user = Auth::user();
        $cart = $user ? $user->cart : null;
        $this->cartItems = $cart ? $cart->items()->with('product')->get() : collect();
    }

    public function removeItem($itemId)
    {
        CartItem::find($itemId)?->delete();
        $this->mount(); // Refresh items
    }

    public function render()
    {
        return view('livewire.cart.cart-panel');
    }
}
