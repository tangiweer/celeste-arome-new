@extends('layouts.app')

@section('content')
@php
    // Be tolerant to different variables your controller may pass
    $items = isset($items) ? $items : (isset($cart) ? $cart->items : collect());
    $subtotal = isset($subtotal) ? $subtotal : ($items?->sum(fn($i) => ($i->unit_price ?? ($i->product->price ?? 0)) * ($i->qty ?? $i->quantity ?? 1)) ?? 0);
    $shipping = isset($shipping) ? $shipping : 0.00;
    $total    = isset($total) ? $total : ($subtotal + $shipping);
@endphp

<div class="max-w-6xl mx-auto p-6">
    {{-- Stepper --}}
    <div class="flex justify-center mb-8">
        <div class="flex items-center text-sm font-medium">
            <span class="px-3 py-1 rounded-full bg-blue-600/30 text-blue-200 ring-1 ring-blue-600/50">1. Cart</span>
            <span class="mx-3 text-blue-300">→</span>
            <span class="px-3 py-1 rounded-full bg-white/10 text-gray-300 ring-1 ring-white/10">2. Checkout</span>
            <span class="mx-3 text-blue-300">→</span>
            <span class="px-3 py-1 rounded-full bg-white/10 text-gray-300 ring-1 ring-white/10">3. Payment</span>
            <span class="mx-3 text-blue-300">→</span>
            <span class="px-3 py-1 rounded-full bg-white/10 text-gray-300 ring-1 ring-white/10">4. Confirmation</span>
        </div>
    </div>

    <h1 class="text-2xl font-bold text-white mb-4">Your Cart</h1>

    <div class="grid lg:grid-cols-3 gap-6">
        {{-- Left: editable basket --}}
        <div class="lg:col-span-2 space-y-4">
            @forelse($items as $item)
                <div class="glass rounded-xl p-4 flex items-center gap-4">
                    {{-- Product image --}}
                    @php $img = $item->product->primaryImage->url ?? $item->product->image_url ?? null; @endphp
                    <div class="w-20 h-20 rounded-lg overflow-hidden bg-white/10 flex items-center justify-center">
                        @if($img)
                            <img src="{{ $img }}" alt="{{ $item->product->name ?? 'Product' }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-xs text-gray-300">No Image</span>
                        @endif
                    </div>

                    {{-- Details --}}
                    <div class="flex-1">
                        <div class="text-white font-semibold">
                            {{ $item->product->name ?? 'Product' }}
                        </div>
                        <div class="text-gray-300 text-sm">
                            ${{ number_format($item->unit_price ?? ($item->product->price ?? 0), 2) }}
                        </div>
                    </div>

                    {{-- Qty update --}}
                    <form method="POST" action="{{ route('cart.updateQty', ['item' => $item->id]) }}" class="flex items-center gap-2">
                        @csrf
                        @method('PATCH')
                        <input
                            name="qty"
                            type="number"
                            min="1"
                            value="{{ $item->qty ?? 1 }}"
                            class="w-20 bg-white/10 text-white rounded-lg px-3 py-2 outline-none ring-1 ring-white/10"
                        >
                        <button class="px-3 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">Update</button>
                    </form>

                    {{-- Remove --}}
                    <form method="POST" action="{{ route('cart.remove', ['item' => $item->id]) }}">
                        @csrf
                        @method('DELETE')
                        <button class="ml-2 px-3 py-2 rounded-lg bg-rose-600 text-white hover:bg-rose-700">Remove</button>
                    </form>

                    {{-- Line total --}}
                    <div class="ml-4 text-right">
                        <div class="text-white font-semibold">
                            ${{ number_format(($item->unit_price ?? ($item->product->price ?? 0)) * ($item->qty ?? 1), 2) }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="glass rounded-xl p-6 text-center text-gray-300">
                    Your cart is empty.
                </div>
            @endforelse
        </div>

        {{-- Right: order summary --}}
        <div>
            <div class="glass rounded-xl p-5 sticky top-6">
                <div class="text-white font-semibold mb-3">Order Summary</div>
                <div class="text-sm text-gray-300 space-y-2">
                    <div class="flex justify-between">
                        <span>Subtotal</span>
                        <span>${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Shipping</span>
                        <span>${{ number_format($shipping, 2) }}</span>
                    </div>
                </div>
                <div class="border-t border-white/10 my-3"></div>
                <div class="flex justify-between text-white font-semibold text-lg">
                    <span>Total</span>
                    <span>${{ number_format($total, 2) }}</span>
                </div>

                <a href="{{ route('checkout.create') }}"
                   class="mt-4 block text-center w-full px-4 py-3 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700">
                    Proceed to Checkout
                </a>
               
            </div>
        </div>
    </div>
</div>

{{-- glass helper --}}
<style>
  .glass{background:rgba(255,255,255,.08);backdrop-filter:blur(10px);border:1px solid rgba(255,255,255,.12)}
</style>
@endsection
