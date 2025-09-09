<div class="max-w-2xl mx-auto bg-white/5 border border-white/10 rounded-xl p-8">
    <h2 class="text-2xl font-bold text-blue-300 mb-6">Shopping Cart</h2>
    @if($cartItems->count())
        <table class="w-full mb-6">
            <thead>
                <tr class="text-blue-200">
                    <th>Product</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Total</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($cartItems as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>${{ number_format($item->product->price,2) }}</td>
                        <td>
                            <input type="number" min="1" wire:change="updateQty({{ $item->id }}, $event.target.value)" value="{{ $item->quantity }}" class="w-16 rounded bg-black/20 border border-white/10 px-2 py-1 text-blue-300">
                        </td>
                        <td>${{ number_format($item->product->price * $item->quantity,2) }}</td>
                        <td><button wire:click="removeItem({{ $item->id }})" class="text-red-400 hover:underline">Remove</button></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="text-right font-bold text-lg mb-6">Total: ${{ number_format($total,2) }}</div>
        <a href="{{ route('checkout.create') }}" class="w-full bg-blue-500 text-white py-2 rounded-lg font-semibold hover:bg-blue-600 transition block text-center">Proceed to Checkout</a>
    @else
        <div class="text-gray-400">Your cart is empty.</div>
    @endif
</div>
