@extends('layouts.admin')

@section('title', 'Edit Order')

@section('content')
<div class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-2xl">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-2xl font-extrabold text-blue-300">Edit Order #{{ $order->id }}</h1>
            <a href="{{ route('admin.orders.show', $order) }}" class="bg-white/10 hover:bg-white/20 text-blue-300 px-4 py-2 rounded-lg transition-colors border border-white/10">
                Back to Order
            </a>
        </div>

        <div class="bg-white/10 rounded-xl p-6 shadow border border-white/10">
            <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-6">
                    <!-- Order Status and Total -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="status" class="block text-sm font-medium text-blue-300 mb-2">Order Status</label>
                            <select name="status" id="status" 
                                    class="w-full rounded-lg bg-black/10 border border-white/10 px-4 py-3 text-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            @error('status')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="total" class="block text-sm font-medium text-blue-300 mb-2">Order Total ($)</label>
                            <input type="number" 
                                   name="total" 
                                   id="total" 
                                   value="{{ old('total', $order->total) }}"
                                   step="0.01"
                                   min="0"
                                   class="w-full rounded-lg bg-black/10 border border-white/10 px-4 py-3 text-blue-200 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="0.00">
                            @error('total')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Order Items (Read-only display) -->
                    <div>
                        <label class="block text-sm font-medium text-blue-300 mb-2">Order Items</label>
                        @if($order->items->count() > 0)
                            <div class="overflow-hidden rounded-lg bg-white/5 border border-white/10">
                                <table class="w-full text-left">
                                    <thead class="text-blue-300 border-b border-white/10 bg-black/20">
                                        <tr>
                                            <th class="px-4 py-3">Product</th>
                                            <th class="px-4 py-3">SKU</th>
                                            <th class="px-4 py-3">Qty</th>
                                            <th class="px-4 py-3">Unit Price</th>
                                            <th class="px-4 py-3">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order->items as $item)
                                            <tr class="border-b border-white/5 hover:bg-blue-900/10">
                                                <td class="px-4 py-3">
                                                    <div class="text-blue-200 font-semibold">{{ $item->product->name ?? 'Product Deleted' }}</div>
                                                </td>
                                                <td class="px-4 py-3 text-gray-400 text-sm">{{ $item->product->sku ?? 'N/A' }}</td>
                                                <td class="px-4 py-3 text-blue-200">{{ $item->quantity }}</td>
                                                <td class="px-4 py-3 text-blue-200">${{ number_format($item->unit_price ?? $item->price, 2) }}</td>
                                                <td class="px-4 py-3 font-semibold text-blue-200">${{ number_format(($item->unit_price ?? $item->price) * $item->quantity, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center text-gray-400 py-8 bg-white/5 rounded-lg border border-white/10">
                                <p>No items found for this order.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Order Information Display -->
                    <div class="bg-white/5 rounded-lg p-4 border border-white/5">
                        <h3 class="text-sm font-medium text-blue-300 mb-3">Order Information</h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-400">Order ID:</span>
                                <span class="text-blue-200 font-semibold ml-2">#{{ $order->id }}</span>
                            </div>
                            <div>
                                <span class="text-gray-400">Customer:</span>
                                <span class="text-blue-200 font-semibold ml-2">{{ $order->user->name ?? 'Unknown' }}</span>
                            </div>
                            <div>
                                <span class="text-gray-400">Email:</span>
                                <span class="text-blue-200 font-semibold ml-2">{{ $order->user->email ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="text-gray-400">Created:</span>
                                <span class="text-blue-200 font-semibold ml-2">{{ $order->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-4 pt-6 border-t border-white/10">
                        <button type="submit" 
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Update Order
                        </button>
                        <a href="{{ route('admin.orders.show', $order) }}" 
                           class="flex-1 text-center bg-white/10 hover:bg-white/20 text-blue-300 px-6 py-3 rounded-lg font-semibold transition-colors border border-white/10">
                            Cancel
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="fixed top-4 right-4 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg z-50">
        {{ session('success') }}
    </div>
@endif

@endsection