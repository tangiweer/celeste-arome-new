<?php
namespace App\Livewire;
use Livewire\Component;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
class ShoppingCart extends Component {
    public $cartItems = [];
    public $total = 0;
    public function mount() {
        $user = Auth::user();
        $cart = $user ? $user->cart : null;
        $this->cartItems = $cart ? $cart->items()->with('product')->get() : collect();
        $this->total = $this->cartItems->sum(fn($item) => $item->product->price * $item->quantity);
    }
    public function updateQty($itemId, $qty) {
        $item = CartItem::find($itemId);
        if ($item && $qty > 0) {
            $item->update(['quantity' => $qty]);
            $this->mount();
        }
    }
    public function removeItem($itemId) {
        CartItem::find($itemId)?->delete();
        $this->mount();
    }
    public function render() {
        return view('livewire.shopping-cart', [
            'cartItems' => $this->cartItems,
            'total' => $this->total,
        ]);
    }
}
