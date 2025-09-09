<?php
namespace App\Livewire\Shop;
use Livewire\Component;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
class MyOrders extends Component
{
    public $orders;
    public function mount() {
        $user = Auth::user();
        $this->orders = $user ? Order::where('user_id', $user->id)->orderByDesc('created_at')->get() : collect();
    }
    public function render() {
        return view('livewire.shop.my-orders');
    }
}
