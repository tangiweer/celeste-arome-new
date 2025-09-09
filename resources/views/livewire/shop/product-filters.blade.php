<div class="w-full">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        {{-- Sidebar: Filters --}}
        <aside class="lg:col-span-1 order-2 lg:order-1">
            <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl p-6 sticky top-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4">
                            </path>
                        </svg>
                        Filters
                    </h3>
                    <button type="button" wire:click="resetFilters"
                        class="text-sm text-blue-300 hover:text-white transition-colors">
                        Reset
                    </button>
                </div>

                {{-- Search --}}
                <div class="mb-6">
                    <label class="block text-sm font-medium text-blue-200 mb-2">Search</label>
                    <div class="relative">
                        <input type="text" wire:model.debounce.500ms="search"
                            class="w-full bg-white/5 border border-white/20 rounded-lg px-10 py-3 text-white placeholder-gray-400 focus:border-blue-400 focus:ring-2 focus:ring-blue-400/20 focus:outline-none transition-all"
                            placeholder="Find products..." />
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m21 21-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>

                {{-- Categories --}}
                <div class="mb-6">
                    <label class="block text-sm font-medium text-blue-200 mb-3">Categories</label>
                    <div class="space-y-2 max-h-40 overflow-y-auto">
                        @foreach ($categories as $category)
                            <label
                                class="flex items-center gap-3 text-white cursor-pointer hover:text-blue-200 transition-colors">
                                <input type="checkbox" value="{{ $category->id }}" wire:model="selectedCategories"
                                    class="rounded bg-white/10 border-white/20 text-blue-500 focus:ring-blue-400/20">
                                <span class="text-sm">{{ $category->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Brands --}}
                <div class="mb-6">
                    <label class="block text-sm font-medium text-blue-200 mb-3">Brands</label>
                    <div class="space-y-2 max-h-40 overflow-y-auto">
                        @foreach ($brands as $brand)
                            <label
                                class="flex items-center gap-3 text-white cursor-pointer hover:text-blue-200 transition-colors">
                                <input type="checkbox" value="{{ $brand }}" wire:model="selectedBrands"
                                    class="rounded bg-white/10 border-white/20 text-blue-500 focus:ring-blue-400/20">
                                <span class="text-sm">{{ $brand }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Price Range --}}
                <div class="mb-6">
                    <label class="block text-sm font-medium text-blue-200 mb-3">Price Range</label>
                    <div class="space-y-2">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="priceRange" value="0-50" wire:model="priceRange"
                                class="w-4 h-4 text-blue-400 bg-white/5 border-white/20 focus:ring-blue-400/20 focus:ring-2" />
                            <span class="ml-2 text-white">$0 - $50</span>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="priceRange" value="50-100" wire:model="priceRange"
                                class="w-4 h-4 text-blue-400 bg-white/5 border-white/20 focus:ring-blue-400/20 focus:ring-2" />
                            <span class="ml-2 text-white">$50 - $100</span>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="priceRange" value="100-200" wire:model="priceRange"
                                class="w-4 h-4 text-blue-400 bg-white/5 border-white/20 focus:ring-blue-400/20 focus:ring-2" />
                            <span class="ml-2 text-white">$100 - $200</span>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="priceRange" value="200-500" wire:model="priceRange"
                                class="w-4 h-4 text-blue-400 bg-white/5 border-white/20 focus:ring-blue-400/20 focus:ring-2" />
                            <span class="ml-2 text-white">$200 - $500</span>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="priceRange" value="500+" wire:model="priceRange"
                                class="w-4 h-4 text-blue-400 bg-white/5 border-white/20 focus:ring-blue-400/20 focus:ring-2" />
                            <span class="ml-2 text-white">$500+</span>
                        </label>
                    </div>
                </div>

                {{-- In Stock --}}
                <div class="mb-6">
                    <label
                        class="flex items-center gap-3 text-white cursor-pointer hover:text-blue-200 transition-colors">
                        <input type="checkbox" wire:model="inStock"
                            class="rounded bg-white/10 border-white/20 text-blue-500 focus:ring-blue-400/20">
                        <span class="text-sm">In stock only</span>
                    </label>
                </div>

                {{-- Sort --}}
                <div class="mb-6">
                    <label class="block text-sm font-medium text-blue-200 mb-3">Sort By</label>
                    <select wire:model="sort"
                        class="w-full bg-white/5 border border-white/20 rounded-lg px-3 py-2 text-white focus:border-blue-400 focus:ring-2 focus:ring-blue-400/20 focus:outline-none transition-all">
                        <option value="latest">Latest</option>
                        <option value="price_low">Price: Low to High</option>
                        <option value="price_high">Price: High to Low</option>
                        <option value="name_az">Name: A to Z</option>
                        <option value="name_za">Name: Z to A</option>
                    </select>
                </div>

                {{-- Apply Filters --}}
                <button type="button" wire:click="applyFilters"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition-colors">
                    Apply Filters
                </button>
            </div>
        </aside>

        {{-- Main Content --}}
        <main class="lg:col-span-3 order-1 lg:order-2">
            {{-- Header --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2">Our Collection</h1>
                    <p class="text-gray-300">Discover amazing products</p>
                </div>
                <div class="mt-4 sm:mt-0">
                    <span class="inline-block bg-white/10 text-gray-300 px-3 py-1 rounded-full text-sm">
                        {{ $totalCount }} {{ \Illuminate\Support\Str::plural('item', $totalCount) }}
                    </span>
                </div>
            </div>

            {{-- Products Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                @forelse($products as $product)
                    <div
                        class="bg-white/5 border border-white/10 rounded-2xl p-5 hover:bg-white/10 hover:border-white/20 transition-all duration-300 group">
                        {{-- Product Image --}}
                        <div class="aspect-[4/3] bg-white/5 rounded-xl overflow-hidden mb-4 relative">
                            @php
                                $img = $product->primaryImage->url ?? ($product->image_url ?? null);
                            @endphp

                            @if ($img)
                                <img src="{{ $img }}" alt="{{ $product->name }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                            @endif

                            {{-- Stock Badge (adjust to your column name) --}}
                            @if (isset($product->stock))
                                @if ($product->stock <= 0)
                                    <div
                                        class="absolute top-3 left-3 bg-red-500 text-white text-xs px-2 py-1 rounded-full font-medium">
                                        Out of Stock
                                    </div>
                                @elseif($product->stock <= 5)
                                    <div
                                        class="absolute top-3 left-3 bg-orange-500 text-white text-xs px-2 py-1 rounded-full font-medium">
                                        Low Stock
                                    </div>
                                @endif
                            @endif
                        </div>

                        {{-- Product Info --}}
                        <div class="space-y-2">
                            <h3 class="text-white font-bold text-lg group-hover:text-blue-200 transition-colors">
                                {{ $product->name }}
                            </h3>

                            <p class="text-gray-300 text-sm line-clamp-2">
                                {{ \Illuminate\Support\Str::limit($product->description, 80) }}
                            </p>

                            @if (!empty($product->brand))
                                <div
                                    class="inline-block bg-blue-600/20 text-blue-300 text-xs px-2 py-1 rounded-full font-medium">
                                    {{ $product->brand }}
                                </div>
                            @endif

                            <div class="flex items-center justify-between pt-3">
                                <div>
                                    <span class="text-white font-bold text-xl">
                                        ${{ number_format($product->price, 2) }}
                                    </span>
                                </div>

                                <a href="{{ route('shop.show', $product->slug) }}"
                                    class="bg-white/10 hover:bg-blue-600 text-white px-4 py-2 rounded-lg border border-white/20 hover:border-blue-500 transition-all font-medium">
                                    View
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    {{-- Empty State --}}
                    <div class="col-span-full text-center py-16">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                        <h3 class="text-xl font-semibold text-white mb-2">No products found</h3>
                        <p class="text-gray-400 mb-4">Try adjusting your filters or search terms</p>
                        <button wire:click="resetFilters"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                            Reset Filters
                        </button>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-12 flex justify-center">
                {{ $products->links() }}
            </div>
        </main>
    </div>
</div>
