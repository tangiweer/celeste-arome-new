<?php

namespace App\Livewire\Shop;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Category;
use App\Models\Product;

class ProductFilters extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';
    public int $perPage = 9;

    // Sidebar data
    public $categories = [];
    public $brands     = [];

    // Filters/state
    public $search = '';
    public $selectedCategories = [];
    public $selectedBrands     = [];
    public $selectedCategory   = '';
    public $brand              = '';
    public $inStock = false;

    // Sort: latest | price_low | price_high | name_az | name_za
    public $sort = 'latest';

    // Price (radios only)
    // Allowed values: '0-50','50-100','100-200','200-500','500+'
    public $priceRange = null;

    // Results
    public $totalCount = 0;

    public function mount(): void
    {
        $this->categories = Category::orderBy('name')->get();

        $this->brands = Product::query()
            ->select('brand')->whereNotNull('brand')->distinct()
            ->orderBy('brand')->pluck('brand')->toArray();

        $this->recount();
    }

    // Any change -> go back to page 1 and refresh counts
    public function updated($property): void
    {
        $this->resetPage();
        $this->recount();
    }

    public function applyFilters(): void
    {
        $this->resetPage();
        $this->recount();
    }

    public function resetFilters(): void
    {
        $this->search = '';
        $this->selectedCategories = [];
        $this->selectedBrands = [];
        $this->selectedCategory = '';
        $this->brand = '';
        $this->inStock = false;
        $this->sort = 'latest';
        $this->priceRange = null;

        $this->resetPage();
        $this->recount();
    }

    // ---------- Internals ----------

    protected function baseQuery()
    {
        $q = Product::query()
            ->with(['primaryImage', 'images'])
            ->where('is_active', true);

        // Search
        if (($s = trim($this->search)) !== '') {
            $q->where(function ($x) use ($s) {
                $x->where('name', 'like', "%{$s}%")
                  ->orWhere('brand', 'like', "%{$s}%")
                  ->orWhere('description', 'like', "%{$s}%");
            });
        }

        // Categories
        if ($this->selectedCategory) {
            $q->where('category_id', $this->selectedCategory);
        }
        if (!empty($this->selectedCategories)) {
            $q->whereIn('category_id', $this->selectedCategories);
        }

        // Brands
        if ($this->brand) {
            $q->where('brand', $this->brand);
        }
        if (!empty($this->selectedBrands)) {
            $q->whereIn('brand', $this->selectedBrands);
        }

        // Price range (half-open intervals to avoid overlap at boundaries)
        // 0-50   => price >= 0   AND price < 50
        // 50-100 => price >= 50  AND price < 100
        // 100-200 => price >= 100 AND price < 200
        // 200-500 => price >= 200 AND price < 500
        // 500+   => price >= 500
        if ($this->priceRange) {
            switch ($this->priceRange) {
                case '0-50':
                    $q->where('price', '>=', 0)->where('price', '<', 50);
                    break;
                case '50-100':
                    $q->where('price', '>=', 50)->where('price', '<', 100);
                    break;
                case '100-200':
                    $q->where('price', '>=', 100)->where('price', '<', 200);
                    break;
                case '200-500':
                    $q->where('price', '>=', 200)->where('price', '<', 500);
                    break;
                case '500+':
                    $q->where('price', '>=', 500);
                    break;
            }
        }

        // Stock
        if ($this->inStock) {
            $q->where('stock', '>', 0);
        }

        // Sort
        switch ($this->sort) {
            case 'price_low':  $q->orderBy('price', 'asc');  break;
            case 'price_high': $q->orderBy('price', 'desc'); break;
            case 'name_az':    $q->orderBy('name', 'asc');   break;
            case 'name_za':    $q->orderBy('name', 'desc');  break;
            case 'latest':
            default:           $q->orderBy('created_at', 'desc'); break;
        }

        return $q;
    }

    protected function recount(): void
    {
        $this->totalCount = $this->baseQuery()->count();
    }

    public function render()
    {
        $products = $this->baseQuery()->paginate($this->perPage);

        return view('livewire.shop.product-filters', [
            'products'   => $products,
            'categories' => $this->categories,
            'brands'     => $this->brands,
            'totalCount' => $this->totalCount,
        ]);
    }
}
