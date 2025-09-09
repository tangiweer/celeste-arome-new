@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-6">
    @if(session('success'))
        <div class="mb-4 rounded-lg p-3 text-emerald-200 ring-1 ring-emerald-600/40" style="background:rgba(15,23,42,.6);backdrop-filter:blur(12px);border:1px solid rgba(16,185,129,.25);">
            {{ session('success') }}
        </div>
    @endif

    {{-- Stepper --}}
    <div class="flex justify-center mb-8">
        <div class="flex items-center text-sm font-medium">
            <span class="px-3 py-1 rounded-full bg-white/10 text-gray-300">1. Cart</span>
            <span class="mx-3 text-blue-300">→</span>
            <span class="px-3 py-1 rounded-full bg-white/10 text-gray-300">2. Checkout</span>
            <span class="mx-3 text-blue-300">→</span>
            <span class="px-3 py-1 rounded-full bg-white/10 text-gray-300">3. Payment</span>
            <span class="mx-3 text-blue-300">→</span>
            <span class="px-3 py-1 rounded-full bg-blue-700/40 text-blue-200 ring-1 ring-blue-500">4. Confirmation</span>
        </div>
    </div>

    <div class="rounded-xl p-8" style="background:rgba(15,23,42,.6);backdrop-filter:blur(12px);border:1px solid rgba(59,130,246,.25);">
        <h1 class="text-2xl font-bold text-white mb-2">Thank you! 🎉</h1>
        <p class="text-blue-200 mb-6">
            Your order{{ session('order_id') ? ' #' . session('order_id') : '' }} has been placed successfully.
        </p>

        <div class="flex flex-wrap gap-3">
            <a href="{{ route('shop.index') }}" class="px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700">Continue Shopping</a>
            <a href="{{ route('account.orders') }}" class="px-4 py-2 rounded-xl bg-white/10 text-blue-200 ring-1 ring-blue-500/40 hover:bg-white/20">View Orders</a>
            @if(session('order_id'))
                <a href="{{ route('account.orders') }}#order-{{ session('order_id') }}" class="px-4 py-2 rounded-xl bg-white/10 text-blue-200 ring-1 ring-blue-500/40 hover:bg-white/20">View This Order</a>
            @endif
        </div>
    </div>
</div>
@endsection
