@extends('layouts.app')
@section('title', $product->name)

@section('content')
@php
    use Illuminate\Support\Str;

    $raw = $product->primaryImage->path ?? null;
    $img = $raw
        ? (Str::startsWith($raw, ['http://','https://'])
            ? $raw
            : asset('storage/'.$raw))
        : 'https://via.placeholder.com/800x600?text=No+Image';

    // Derived fields with fallbacks
    $brand   = trim($product->brand ?? '');
    $price   = (float)($product->price ?? 0);
    $sku     = $product->sku ?? null;
    $inStock = (int)($product->stock ?? 0) > 0;
    $stockQty= (int)($product->stock ?? 0);
@endphp

<div class="max-w-7xl mx-auto py-10">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

        {{-- Image --}}
        <div class="rounded-2xl overflow-hidden bg-white/10 backdrop-blur border border-white/10 flex items-center justify-center">
            <img class="w-full h-[480px] object-cover" src="{{ $img }}" alt="{{ $product->name }}">
        </div>

        {{-- Details card --}}
        <div class="bg-gradient-to-b from-black via-blue-900 to-black rounded-2xl shadow-xl border border-white/10 p-8">
            <div class="flex items-start justify-between gap-3 mb-4">
                <div>
                    <h1 class="text-3xl font-bold text-blue-200 leading-tight">{{ $product->name }}</h1>
                    @if($brand)
                        <div class="text-blue-400 font-medium mt-1">{{ $brand }}</div>
                    @endif
                    <div class="mt-2 flex flex-wrap items-center gap-2">
                        @if($sku)
                            <span class="text-xs text-gray-300/80 px-2 py-1 rounded bg-white/5 border border-white/10">SKU: {{ $sku }}</span>
                        @endif
                        <span class="text-xs px-2 py-1 rounded border
                            {{ $inStock ? 'bg-green-500/10 border-green-500/30 text-green-300' : 'bg-rose-500/10 border-rose-500/30 text-rose-300' }}">
                            {{ $inStock ? "In stock ($stockQty)" : 'Out of stock' }}
                        </span>
                    </div>
                </div>

                {{-- Wishlist toggle kept clickable (higher z-index) --}}
                <div class="relative z-10">
                    @auth
                        @livewire('shop.heart-toggle', ['product' => $product], key('product-'.$product->id))
                    @else
                        <a href="{{ Route::has('login') ? route('login') : url('/login') }}"
                           class="text-sm text-blue-300 hover:text-blue-200 underline">Sign in to save ♥</a>
                    @endauth
                </div>
            </div>

            {{-- Description (guarded) --}}
            @if(!empty($product->description))
                <p class="mb-6 text-gray-200/90 leading-relaxed">
                    {{ Str::limit(strip_tags($product->description), 600) }}
                </p>
            @endif

            {{-- Price --}}
            <div class="mb-6">
                <div class="font-semibold text-gray-200 mb-1">Price</div>
                <div class="text-blue-200 text-2xl font-extrabold tracking-wide">
                    ${{ number_format($price, 2) }}
                </div>
            </div>

            {{-- Add to Cart --}}
            <form method="POST" action="{{ Route::has('cart.add') ? route('cart.add', $product) : url('/cart/add/'.$product->id) }}" class="space-y-4">
                @csrf

                {{-- Quantity selector (disabled if OOS) --}}
                <div class="flex items-center gap-4">
                    <label for="qty" class="text-sm text-gray-200">Quantity</label>
                    <input id="qty" name="quantity" type="number" min="1" value="1"
                           max="{{ max($stockQty,1) }}"
                           class="w-24 px-3 py-2 bg-white/10 border border-white/10 rounded-lg text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-600"
                           {{ $inStock ? '' : 'disabled' }}>
                </div>

                <button type="submit"
                        class="w-full px-4 py-3 rounded-lg bg-blue-600 text-white font-bold hover:bg-blue-500 transition disabled:opacity-50 disabled:cursor-not-allowed"
                        {{ $inStock ? '' : 'disabled' }}>
                    {{ $inStock ? 'Add to Cart' : 'Out of Stock' }}
                </button>
            </form>

            {{-- Meta (optional) --}}
            @if(!empty($product->category?->name))
                <div class="mt-6 text-sm text-gray-300">
                    Category:
                    <span class="text-blue-300">{{ $product->category->name }}</span>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
