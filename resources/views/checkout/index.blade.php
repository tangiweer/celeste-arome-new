@extends('layouts.app')

@section('content')
@php
    $items = isset($cart) ? $cart->items : (isset($items) ? $items : collect());
    $subtotal = isset($subtotal) ? $subtotal : ($items?->sum(fn($i) => ($i->unit_price ?? ($i->product->price ?? 0)) * ($i->qty ?? 1)) ?? 0);
    $shipping = isset($shipping) ? $shipping : 0.00;
    $total    = isset($total) ? $total : ($subtotal + $shipping);
@endphp

<div class="max-w-6xl mx-auto p-6">
    {{-- Stepper --}}
    <div class="flex justify-center mb-8">
        <div class="flex items-center text-sm font-medium">
            <span class="px-3 py-1 rounded-full bg-white/10 text-gray-300">1. Cart</span>
            <span class="mx-3 text-blue-300">→</span>
            <span class="px-3 py-1 rounded-full bg-blue-700/40 text-blue-200 ring-1 ring-blue-500">2. Checkout</span>
            <span class="mx-3 text-blue-300">→</span>
            <span class="px-3 py-1 rounded-full bg-white/10 text-gray-300">3. Payment</span>
            <span class="mx-3 text-blue-300">→</span>
            <span class="px-3 py-1 rounded-full bg-white/10 text-gray-300">4. Confirmation</span>
        </div>
    </div>

    <h1 class="text-2xl font-bold text-white mb-4">Checkout</h1>

    <div class="grid lg:grid-cols-3 gap-6">
        {{-- Left: shipping + summary table --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="glass rounded-xl p-5">
                <h2 class="text-white font-semibold mb-4">Shipping Information</h2>
                <div class="grid md:grid-cols-2 gap-4">
                    <input type="text" placeholder="Full Name" class="w-full p-3 rounded-lg bg-blue-950/40 text-white outline-none ring-1 ring-blue-700">
                    <input type="email" placeholder="Email" class="w-full p-3 rounded-lg bg-blue-950/40 text-white outline-none ring-1 ring-blue-700">
                    <input type="text" placeholder="Phone" class="w-full p-3 rounded-lg bg-blue-950/40 text-white outline-none ring-1 ring-blue-700 md:col-span-2">
                    <input type="text" placeholder="Address line 1" class="w-full p-3 rounded-lg bg-blue-950/40 text-white outline-none ring-1 ring-blue-700 md:col-span-2">
                    <input type="text" placeholder="City" class="w-full p-3 rounded-lg bg-blue-950/40 text-white outline-none ring-1 ring-blue-700">
                    <input type="text" placeholder="Postal Code" class="w-full p-3 rounded-lg bg-blue-950/40 text-white outline-none ring-1 ring-blue-700">
                </div>
            </div>

            <div class="glass rounded-xl p-5">
                <h2 class="text-white font-semibold mb-4">Order Items</h2>
                <div class="overflow-hidden rounded-lg">
                    <table class="w-full text-sm">
                        <thead class="bg-blue-900/50 text-blue-200">
                            <tr>
                                <th class="text-left p-3 font-medium">Product</th>
                                <th class="text-center p-3 font-medium">Qty</th>
                                <th class="text-right p-3 font-medium">Line Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-blue-800/40">
                            @foreach($items as $i)
                                <tr>
                                    <td class="p-3 text-white">
                                        {{ $i->product->name ?? 'Product' }}
                                        <div class="text-xs text-gray-400">
                                            ${{ number_format($i->unit_price ?? ($i->product->price ?? 0), 2) }} each
                                        </div>
                                    </td>
                                    <td class="p-3 text-center text-blue-200">{{ $i->qty ?? 1 }}</td>
                                    <td class="p-3 text-right text-white font-semibold">
                                        ${{ number_format(($i->unit_price ?? ($i->product->price ?? 0)) * ($i->qty ?? 1), 2) }}
                                    </td>
                                </tr>
                            @endforeach
                            @if($items->isEmpty())
                                <tr><td colspan="3" class="p-4 text-center text-gray-400">No items found.</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Right: summary + button --}}
        <div>
            <div class="glass rounded-xl p-5 sticky top-6">
                <div class="text-white font-semibold mb-3">Order Summary</div>
                <div class="text-sm text-blue-200 space-y-2">
                    <div class="flex justify-between"><span>Subtotal</span><span>${{ number_format($subtotal, 2) }}</span></div>
                    <div class="flex justify-between"><span>Shipping</span><span>${{ number_format($shipping, 2) }}</span></div>
                </div>
                <div class="border-t border-blue-800 my-3"></div>
                <div class="flex justify-between text-white font-semibold text-lg">
                    <span>Total</span><span>${{ number_format($total, 2) }}</span>
                </div>

                {{-- FIX: post to checkout.store (creates order) --}}
                <form method="POST" action="{{ route('checkout.store') }}" class="mt-4">
                    @csrf
                    <button type="submit" class="w-full px-4 py-3 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700">
                        Proceed to Payment
                    </button>
                </form>

                <a href="{{ route('cart.index') }}" class="block text-center mt-3 text-gray-400 underline">Back to Cart</a>
            </div>
        </div>
    </div>
</div>

<style>
  .glass { background: rgba(15,23,42,.6); backdrop-filter: blur(12px); border: 1px solid rgba(59,130,246,.25); }
</style>
@endsection
