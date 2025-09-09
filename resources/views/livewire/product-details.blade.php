<div class="max-w-2xl mx-auto bg-white/5 border border-white/10 rounded-xl p-8">
    <h2 class="text-2xl font-bold text-blue-300 mb-4">{{ $product->name }}</h2>
    <div class="mb-2 text-lg">${{ number_format($product->price,2) }}</div>
    <div class="mb-4 text-sm text-gray-400">@if($inStock) In Stock @else Out of Stock @endif</div>
    <form wire:submit.prevent="addToCart" class="mb-6">
        <label for="quantity" class="block mb-2 text-blue-200">Quantity</label>
        <input type="number" id="quantity" wire:model="quantity" min="1" max="{{ $product->stock }}" class="rounded-lg px-4 py-2 bg-black/20 border border-white/10 text-blue-300 mb-4">
        <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg font-semibold hover:bg-blue-600 transition">Add to Cart</button>
    </form>
    @if(session('success'))
        <div class="mt-4 text-green-400">{{ session('success') }}</div>
    @endif
    <div class="mt-8 text-gray-300">{{ $product->description }}</div>
</div>
