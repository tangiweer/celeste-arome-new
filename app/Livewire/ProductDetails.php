<?php
namespace App\Livewire;
use Livewire\Component;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
class ProductDetails extends Component {
    public $product;
    public $quantity = 1;
    public function mount($slug) {
        $this->product = Product::where('slug', $slug)->firstOrFail();
    }
    public function addToCart() {
        $user = Auth::user();
        if (!$user) return redirect()->route('login');
        $cart = $user->cart;
        CartItem::updateOrCreate([
            'cart_id' => $cart->id,
            'product_id' => $this->product->id,
        ], [
            'quantity' => $this->quantity,
        ]);
        session()->flash('success', 'Added to cart!');
    }
    public function render() {
        return view('livewire.product-details', [
            'product' => $this->product,
            'inStock' => $this->product->stock > 0,
        ]);
    }
}
