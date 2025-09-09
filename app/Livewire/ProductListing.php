<?php
namespace App\Livewire;
use Livewire\Component;
use App\Models\Product;
class ProductListing extends Component {
    public $search = '';
    public $category = null;
    public $minPrice = null;
    public $maxPrice = null;
    public function render() {
        $query = Product::query()->where('is_active', 1);
        if ($this->search) {
            $query->where('name', 'like', '%'.$this->search.'%');
        }
        if ($this->category) {
            $query->where('category_id', $this->category);
        }
        if ($this->minPrice) {
            $query->where('price', '>=', $this->minPrice);
        }
        if ($this->maxPrice) {
            $query->where('price', '<=', $this->maxPrice);
        }
        $products = $query->orderByDesc('id')->paginate(12);
        return view('livewire.product-listing', compact('products'));
    }
}
