<div class="max-w-2xl mx-auto bg-white/5 border border-white/10 rounded-xl p-8">
    <h2 class="text-2xl font-bold text-blue-300 mb-6">Checkout</h2>
    <form wire:submit.prevent="submit">
        <div class="mb-4">
            <label class="block text-blue-200 mb-2">Address</label>
            <input type="text" wire:model="address" class="w-full rounded-lg bg-black/20 border border-white/10 px-4 py-2 text-blue-300" required>
        </div>
        <div class="mb-4 grid grid-cols-2 gap-4">
            <div>
                <label class="block text-blue-200 mb-2">City</label>
                <input type="text" wire:model="city" class="w-full rounded-lg bg-black/20 border border-white/10 px-4 py-2 text-blue-300" required>
            </div>
            <div>
                <label class="block text-blue-200 mb-2">State</label>
                <input type="text" wire:model="state" class="w-full rounded-lg bg-black/20 border border-white/10 px-4 py-2 text-blue-300" required>
            </div>
        </div>
        <div class="mb-4 grid grid-cols-2 gap-4">
            <div>
                <label class="block text-blue-200 mb-2">ZIP Code</label>
                <input type="text" wire:model="zip" class="w-full rounded-lg bg-black/20 border border-white/10 px-4 py-2 text-blue-300" required>
            </div>
            <div>
                <label class="block text-blue-200 mb-2">Country</label>
                <input type="text" wire:model="country" class="w-full rounded-lg bg-black/20 border border-white/10 px-4 py-2 text-blue-300" required>
            </div>
        </div>
        <div class="mb-4">
            <label class="block text-blue-200 mb-2">Shipping Method</label>
            <select wire:model="shipping_method" class="w-full rounded-lg bg-black/20 border border-white/10 px-4 py-2 text-blue-300">
                <option value="standard">Standard</option>
                <option value="express">Express</option>
            </select>
        </div>
        <h3 class="text-lg font-semibold text-blue-200 mt-8 mb-4">Order Summary</h3>
        <ul class="mb-6">
            @foreach($cartItems as $item)
                <li class="flex justify-between py-2 border-b border-white/10">
                    <span>{{ $item->product->name }} x{{ $item->quantity }}</span>
                    <span>${{ number_format($item->product->price * $item->quantity,2) }}</span>
                </li>
            @endforeach
        </ul>
        <div class="text-right font-bold text-lg mb-6">Total: ${{ number_format($total,2) }}</div>
        <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg font-semibold hover:bg-blue-600 transition">Place Order & Pay</button>
        @if(session('success'))
            <div class="mt-4 text-green-400">{{ session('success') }}</div>
        @endif
    </form>
</div>
