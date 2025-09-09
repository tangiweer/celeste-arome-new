@extends('layouts.admin')

@section('title', 'Order Details')
@section('page-title', 'Order Details')

@section('content')
<div class="flex items-center justify-between mb-8">
    <h1 class="text-2xl font-extrabold text-blue-300">Order #{{ $order->id }}</h1>
    <div class="flex gap-3">
        <!-- <a href="{{ route('admin.orders.edit', $order) }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
            Edit Order
        </a> -->
        <a href="{{ route('admin.orders.index') }}" 
           class="bg-white/10 hover:bg-white/20 text-blue-300 px-4 py-2 rounded-lg transition-colors border border-white/10">
            Back to Orders
        </a>
    </div>
</div>

<div class="max-w-7xl mx-auto">
    <!-- Order Info and Stats Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8 items-start">
        <!-- Order Info Card -->
        <div class="lg:col-span-1">
            <div class="bg-white/10 rounded-xl p-6 shadow border border-white/10 text-center" style="height: 612px; display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <div class="w-24 h-24 bg-gradient-to-br from-green-500 to-blue-600 rounded-full mx-auto mb-6 flex items-center justify-center">
                        <span class="text-3xl font-bold text-white">#{{ $order->id }}</span>
                    </div>
                    <h3 class="text-xl font-bold text-blue-300 mb-2">Order #{{ $order->id }}</h3>
                    <p class="text-gray-400 mb-6">{{ $order->user->name ?? 'Unknown Customer' }}</p>
                </div>
                
                <div class="space-y-4">
                    <div class="bg-white/5 rounded-lg p-4">
                        <div class="text-sm text-gray-400 mb-1">Customer Email</div>
                        <div class="text-blue-200 font-semibold">{{ $order->user->email ?? 'N/A' }}</div>
                    </div>
                    <div class="bg-white/5 rounded-lg p-4">
                        <div class="text-sm text-gray-400 mb-1">Order Date</div>
                        <div class="text-blue-200 font-semibold">{{ $order->created_at->format('M d, Y H:i') }}</div>
                    </div>
                    <div class="bg-white/5 rounded-lg p-4">
                        <div class="text-sm text-gray-400 mb-1">Last Updated</div>
                        <div class="text-blue-200 font-semibold">{{ $order->updated_at->diffForHumans() }}</div>
                    </div>
                    <div class="bg-white/5 rounded-lg p-4">
                        <div class="text-sm text-gray-400 mb-2">Status</div>
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                            {{ $order->status === 'completed' ? 'bg-green-600/20 text-green-300' : 
                               ($order->status === 'pending' ? 'bg-yellow-600/20 text-yellow-300' : 
                               ($order->status === 'processing' ? 'bg-blue-600/20 text-blue-300' : 
                               ($order->status === 'cancelled' ? 'bg-red-600/20 text-red-300' : 'bg-gray-600/20 text-gray-300'))) }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="lg:col-span-1">
            <div class="grid grid-cols-1 gap-6">
                <div class="bg-white/10 rounded-xl p-8 text-center border border-white/10" style="height: 200px;">
                    <div class="text-3xl font-bold text-blue-300 mb-3">${{ number_format($order->total, 2) }}</div>
                    <div class="text-lg text-gray-400">Order Total</div>
                </div>
                <div class="bg-white/10 rounded-xl p-8 text-center border border-white/10" style="height: 200px;">
                    <div class="text-3xl font-bold text-blue-300 mb-3">{{ $order->items->count() }}</div>
                    <div class="text-lg text-gray-400">Total Items</div>
                </div>
                <div class="bg-white/10 rounded-xl p-8 text-center border border-white/10" style="height: 200px;">
                    <div class="text-3xl font-bold text-blue-300 mb-3">${{ number_format($order->total, 2) }}</div>
                    <div class="text-lg text-gray-400">Subtotal</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Full Width Order Items Table -->
    <div class="bg-white/10 rounded-xl p-6 shadow border border-white/10">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-blue-300">Order Items</h3>
            <div class="text-blue-400 text-sm">{{ $order->items->count() }} item(s)</div>
        </div>
        
        @if($order->items->count() > 0)
            <div class="overflow-x-auto rounded-lg bg-white/5">
                <table class="w-full text-left min-w-full">
                    <thead class="text-blue-300 border-b border-white/10 bg-black/20">
                        <tr>
                            <th class="px-6 py-4 text-sm font-semibold">Product</th>
                            <th class="px-6 py-4 text-sm font-semibold">SKU</th>
                            <th class="px-6 py-4 text-sm font-semibold">Quantity</th>
                            <th class="px-6 py-4 text-sm font-semibold">Unit Price</th>
                            <th class="px-6 py-4 text-sm font-semibold">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr class="border-b border-white/5 hover:bg-blue-900/10 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-blue-200">{{ $item->product->name ?? 'Product Deleted' }}</div>
                                    @if($item->product && $item->product->description)
                                        <div class="text-xs text-gray-500 mt-1">{{ Str::limit($item->product->description, 50) }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-300">
                                    <span class="bg-gray-600/20 px-2 py-1 rounded text-xs">
                                        {{ $item->product->sku ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="bg-blue-600/20 text-blue-300 px-3 py-1 rounded-full text-sm font-semibold">
                                        {{ $item->quantity }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-semibold text-blue-200">${{ number_format($item->unit_price ?? $item->price, 2) }}</td>
                                <td class="px-6 py-4 font-bold text-blue-200">${{ number_format(($item->unit_price ?? $item->price) * $item->quantity, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Order Summary Row -->
            <div class="mt-6 border-t border-white/10 pt-6">
                <div class="flex justify-end">
                    <div class="w-80 space-y-3">
                        <div class="flex justify-between items-center text-gray-400">
                            <span>Subtotal:</span>
                            <span class="font-semibold">${{ number_format($order->total, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center border-t border-white/10 pt-3">
                            <span class="text-blue-300 font-bold text-lg">Total:</span>
                            <span class="text-blue-200 font-bold text-xl">${{ number_format($order->total, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center text-gray-400 py-16">
                <div class="w-20 h-20 bg-gray-600/20 rounded-full mx-auto mb-6 flex items-center justify-center">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <p class="text-xl font-semibold mb-2">No items found</p>
                <p class="text-sm">This order doesn't contain any items.</p>
            </div>
        @endif
    </div>

    <!-- Customer Information Card
    <div class="bg-white/10 rounded-xl p-6 shadow border border-white/10 mt-8">
        <h3 class="text-xl font-bold text-blue-300 mb-6">Customer Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center">
                <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-blue-600 rounded-full mx-auto mb-4 flex items-center justify-center">
                    <span class="text-xl font-bold text-white">{{ strtoupper(substr($order->user->name ?? 'U', 0, 1)) }}</span>
                </div>
                <div class="text-blue-200 font-semibold">{{ $order->user->name ?? 'Unknown' }}</div>
                <div class="text-gray-400 text-sm">{{ $order->user->email ?? 'N/A' }}</div>
            </div>
            <div class="space-y-3">
                <div class="bg-white/5 rounded-lg p-3">
                    <div class="text-xs text-gray-400">Customer ID</div>
                    <div class="text-blue-200 font-semibold">#{{ $order->user->id ?? 'N/A' }}</div>
                </div>
                <div class="bg-white/5 rounded-lg p-3">
                    <div class="text-xs text-gray-400">Member Since</div>
                    <div class="text-blue-200 font-semibold">{{ $order->user->created_at->format('M d, Y') ?? 'N/A' }}</div>
                </div>
            </div>
            <div class="space-y-3">
                <div class="bg-white/5 rounded-lg p-3">
                    <div class="text-xs text-gray-400">Total Orders</div>
                    <div class="text-blue-200 font-semibold">{{ $order->user->orders->count() ?? '0' }}</div>
                </div>
                <div class="bg-white/5 rounded-lg p-3">
                    <div class="text-xs text-gray-400">Total Spent</div>
                    <div class="text-blue-200 font-semibold">${{ number_format($order->user->orders->sum('total') ?? 0, 0) }}</div>
                </div>
            </div>
        </div>
    </div> -->
</div>
@endsection