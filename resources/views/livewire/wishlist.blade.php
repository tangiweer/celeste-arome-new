<div class="max-w-3xl mx-auto mt-10 bg-white/10 backdrop-blur border border-white/10 rounded-xl p-8">
    <h2 class="text-2xl font-bold text-blue-200 mb-6">My Wishlist</h2>
    
    @if($wishlist->count() > 0)
        <div class="flex justify-end mb-4">
            <button
                wire:click="addAllToCart"
                wire:loading.attr="disabled"
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-500 transition disabled:opacity-60"
            >
                <span wire:loading.remove>Add all to cart</span>
                <span wire:loading>Adding…</span>
            </button>
        </div>
    @endif
    
    <table class="w-full text-left rounded-xl overflow-hidden">
        <thead class="bg-blue-900/30">
            <tr>
                <th class="px-4 py-2 text-blue-300">Remove</th>
                <th class="px-4 py-2 text-blue-300">Product</th>
                <th class="px-4 py-2 text-blue-300">Unit Price</th>
                <th class="px-4 py-2 text-blue-300">Added On</th>
                <th class="px-4 py-2 text-blue-300">Stock</th>
                <th class="px-4 py-2 text-blue-300">Add to Cart</th>
            </tr>
        </thead>
        <tbody class="bg-white/5">
            @forelse($wishlist as $item)
                @if($item->product)
                <tr class="border-b border-white/10 hover:bg-blue-900/10 transition">
                    <td class="px-4 py-2">
                        <button wire:click="remove({{ $item->id }})" 
                                class="px-2 py-1 bg-red-600 text-white rounded-full hover:bg-red-700 transition"
                                title="Remove from wishlist">
                            &#10006;
                        </button>
                    </td>
                    <td class="px-4 py-2 flex items-center gap-3">
                        {{-- use url accessor for product primary image --}}
                        <img src="{{ optional($item->product->primaryImage)->url ?? 'https://via.placeholder.com/40x40' }}" 
                             alt="{{ $item->product->name }}" 
                             class="w-10 h-10 object-cover rounded">
                        <a href="{{ route('shop.show', $item->product->slug) }}" 
                           class="text-blue-300 font-semibold hover:underline">
                            {{ $item->product->name }}
                        </a>
                    </td>
                    <td class="px-4 py-2 text-blue-100 font-bold">
                        ${{ number_format($item->product->price, 2) }}
                    </td>
                    <td class="px-4 py-2 text-gray-400">
                        {{ $item->created_at->format('F d, Y') }}
                    </td>
                    <td class="px-4 py-2">
                        @if($item->product->stock > 0)
                            <span class="px-3 py-1 rounded-full bg-green-700/30 text-green-300 text-xs">
                                In Stock ({{ $item->product->stock }})
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-full bg-red-700/30 text-red-300 text-xs">
                                Out of Stock
                            </span>
                        @endif
                    </td>
                    <td class="px-4 py-2">
                        @if($item->product->stock > 0)
                            <button
                                wire:click="addToCart({{ $item->id }})"
                                wire:loading.attr="disabled"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-500 transition disabled:opacity-60">
                                <span wire:loading.remove wire:target="addToCart({{ $item->id }})">Add to cart</span>
                                <span wire:loading wire:target="addToCart({{ $item->id }})">Adding…</span>
                            </button>
                        @else
                            <button disabled 
                                    class="px-4 py-2 bg-gray-600 text-gray-400 rounded cursor-not-allowed">
                                Out of Stock
                            </button>
                        @endif
                    </td>
                </tr>
                @endif
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-blue-200">
                        <div class="flex flex-col items-center gap-4">
                            <svg class="w-16 h-16 text-blue-300/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            <div>
                                <h3 class="text-lg font-semibold mb-2">Your wishlist is empty</h3>
                                <p class="text-blue-300/70">Start adding items you love to your wishlist!</p>
                            </div>
                            <a href="{{ route('shop.index') }}" 
                               class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-500 transition">
                                Browse Products
                            </a>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
