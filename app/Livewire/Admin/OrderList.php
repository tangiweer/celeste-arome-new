<?php
namespace App\Livewire\Admin;
use Livewire\Component;
use App\Models\Order;
class OrderList extends Component
{
    public $orders;
    public function mount() {
        $this->orders = Order::with('user')->get();
    }
    public function render() {
        return view('livewire.admin.order-list');
    }
}
