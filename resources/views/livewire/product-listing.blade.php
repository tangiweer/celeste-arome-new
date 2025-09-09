<div>
    <div class="mb-6 flex gap-4">
        <input type="text" wire:model.debounce.500ms="search" placeholder="Search products..." class="rounded-lg px-4 py-2 bg-black/20 border border-white/10 text-blue-300">
        <select wire:model="category" class="rounded-lg px-4 py-2 bg-black/20 border border-white/10 text-blue-300">
            <option value="">All Categories</option>
            @foreach(\App\Models\Category::all() as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>
        <input type="number" wire:model="minPrice" placeholder="Min Price" class="rounded-lg px-4 py-2 bg-black/20 border border-white/10 text-blue-300" min="0">
        <input type="number" wire:model="maxPrice" placeholder="Max Price" class="rounded-lg px-4 py-2 bg-black/20 border border-white/10 text-blue-300" min="0">
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($products as $product)
            <div class="bg-white/5 border border-white/10 rounded-xl p-4">
                <h3 class="font-bold text-blue-200 mb-2">{{ $product->name }}</h3>
                <div class="mb-2">${{ number_format($product->price,2) }}</div>
                <a href="{{ route('shop.show', $product->slug) }}" class="text-blue-400 hover:underline">View Details</a>
            </div>
        @endforeach
    </div>
    <div class="mt-6">{{ $products->links() }}</div>
</div>
