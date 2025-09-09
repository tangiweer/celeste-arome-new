<?php

namespace App\Livewire;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class OrderHistory extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public int $perPage = 8;   // adjust page size if you want

    public function render()
    {
        $orders = Order::where('user_id', Auth::id())
            ->latest()
            ->paginate($this->perPage);

        // IMPORTANT: point to your existing view file
        return view('livewire.order-history', compact('orders'));
    }
}
