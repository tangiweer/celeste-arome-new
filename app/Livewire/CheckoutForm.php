<?php
namespace App\Livewire;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\CartItem;
class CheckoutForm extends Component {
    use WithFileUploads;
    public $address = '';
    public $city = '';
    public $state = '';
    public $zip = '';
    public $country = '';
    public $shipping_method = 'standard';
    public $cartItems = [];
    public $total = 0;
    public function mount() {
        $user = Auth::user();
        $cart = $user ? $user->cart : null;
        $this->cartItems = $cart ? $cart->items()->with('product')->get() : collect();
        $this->total = $this->cartItems->sum(fn($item) => $item->product->price * $item->quantity);
    }
    public function submit() {
        $this->validate([
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'country' => 'required',
        ]);
        $order = Order::create([
            'user_id' => Auth::id(),
            'status' => 'pending',
            'subtotal' => $this->total,
            'total' => $this->total,
            // Add address fields if needed
        ]);
        // Attach items to order
        foreach($this->cartItems as $item) {
            $order->items()->create([
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
            ]);
        }
        session()->flash('success', 'Order placed! Proceed to payment.');
        return redirect()->route('payment.index', ['order_id' => $order->id]);
    }
    public function render() {
        return view('livewire.checkout-form', [
            'cartItems' => $this->cartItems,
            'total' => $this->total,
        ]);
    }
}
