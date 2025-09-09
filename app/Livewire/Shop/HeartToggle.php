<?php
namespace App\Livewire\Shop;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Wishlist;
class HeartToggle extends Component
{
    public Product $product;
    public bool $inWishlist = false;

    public function mount(Product $product)
    {
        $user = Auth::user();
        $this->inWishlist = $user && $user->wishlist()->where('product_id', $product->id)->exists();
    }

    public function toggleWishlist()
    {
        $user = Auth::user();
        if (!$user) return;
        if ($this->inWishlist) {
            $user->wishlist()->where('product_id', $this->product->id)->delete();
            $this->inWishlist = false;
        } else {
            $user->wishlist()->create(['product_id' => $this->product->id]);
            $this->inWishlist = true;
        }
    }

    public function render()
    {
        return view('livewire.shop.heart-toggle');
    }
}
