@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto p-6">
    {{-- Flash / validation messages --}}
    @if(session('error'))
        <div class="mb-4 rounded-lg p-3 text-rose-200 ring-1 ring-rose-600/40 glass">
            {{ session('error') }}
        </div>
    @endif
    @if(session('success'))
        <div class="mb-4 rounded-lg p-3 text-emerald-200 ring-1 ring-emerald-600/40 glass">
            {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="mb-4 rounded-lg p-3 text-rose-200 ring-1 ring-rose-600/40 glass">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Stepper --}}
    <div class="flex justify-center mb-8">
        <div class="flex items-center text-sm font-medium">
            <span class="px-3 py-1 rounded-full bg-white/10 text-gray-300">1. Cart</span>
            <span class="mx-3 text-blue-300">→</span>
            <span class="px-3 py-1 rounded-full bg-white/10 text-gray-300">2. Checkout</span>
            <span class="mx-3 text-blue-300">→</span>
            <span class="px-3 py-1 rounded-full bg-blue-700/40 text-blue-200 ring-1 ring-blue-500">3. Payment</span>
            <span class="mx-3 text-blue-300">→</span>
            <span class="px-3 py-1 rounded-full bg-white/10 text-gray-300">4. Confirmation</span>
        </div>
    </div>

    <h1 class="text-2xl font-bold text-white mb-4">Payment</h1>

    <div class="grid lg:grid-cols-3 gap-6">
        {{-- Left: payment method + items --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Payment method --}}
            <div class="glass rounded-xl p-5">
                <h2 class="text-white font-semibold mb-4">Choose Payment Method</h2>

                <form id="payment-form" method="POST" action="{{ route('payment.process') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="order_id" value="{{ $order->id }}">

                    <label class="flex items-center gap-3 text-blue-200">
                        <input
                            type="radio"
                            name="payment_method"
                            value="cod"
                            class="accent-blue-600"
                            checked
                        >
                        <span>Cash on Delivery</span>
                    </label>

                    @error('payment_method')
                        <div class="text-rose-300 text-sm">{{ $message }}</div>
                    @enderror

                    <button id="pay-btn" type="submit"
                            class="w-full px-4 py-3 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700">
                        Place Order (Cash on Delivery)
                    </button>
                </form>
            </div>

            {{-- Items read-only --}}
            <div class="glass rounded-xl p-5">
                <h2 class="text-white font-semibold mb-4">Order #{{ $order->id }} Items</h2>
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
                            @forelse ($order->items as $i)
                                @php
                                    $price = $i->unit_price ?? $i->price ?? 0;
                                    $qty   = $i->qty ?? $i->quantity ?? 1;
                                @endphp
                                <tr>
                                    <td class="p-3 text-white">
                                        {{ $i->product->name ?? 'Product' }}
                                        <div class="text-xs text-gray-400">${{ number_format($price, 2) }} each</div>
                                    </td>
                                    <td class="p-3 text-center text-blue-200">{{ $qty }}</td>
                                    <td class="p-3 text-right text-white font-semibold">
                                        ${{ number_format($price * $qty, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="p-4 text-center text-gray-400">No items found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Right: totals --}}
        <div>
            <div class="glass rounded-xl p-5 sticky top-6">
                <div class="text-white font-semibold mb-3">Amount Due</div>
                <div class="text-sm text-blue-200 space-y-2">
                    <div class="flex justify-between">
                        <span>Subtotal</span>
                        <span>${{ number_format($order->subtotal ?? 0, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Shipping</span>
                        <span>${{ number_format($order->shipping ?? 0, 2) }}</span>
                    </div>
                </div>
                <div class="border-t border-blue-800 my-3"></div>
                <div class="flex justify-between text-white font-semibold text-lg">
                    <span>Total</span>
                    <span>${{ number_format($order->total ?? 0, 2) }}</span>
                </div>

                <a href="{{ route('checkout.create') }}" class="block text-center mt-4 text-gray-400 underline">
                    Back to Checkout
                </a>
            </div>
        </div>
    </div>
</div>

<style>
  .glass { background: rgba(15,23,42,.6); backdrop-filter: blur(12px); border: 1px solid rgba(59,130,246,.25); }
</style>
@endsection
