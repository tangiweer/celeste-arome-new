@extends('layouts.admin')

@section('title', 'Customer Details')

@section('content')
<div class="flex items-center justify-between mb-8">
    <h1 class="text-2xl font-extrabold text-blue-300">Customer Details</h1>
    <div class="flex gap-3">
        <!-- <a href="{{ route('admin.customers.edit', $customer) }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
            Edit Customer
        </a> -->
        <a href="{{ route('admin.customers.index') }}" 
           class="bg-white/10 hover:bg-white/20 text-blue-300 px-4 py-2 rounded-lg transition-colors border border-white/10">
            Back to Customers
        </a>
    </div>
</div>

<div class="max-w-7xl mx-auto">
    <!-- Customer Profile and Stats Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8 items-start">
        <!-- Customer Profile Card -->
        <div class="lg:col-span-1">
            <div class="bg-white/10 rounded-xl p-6 shadow border border-white/10 text-center" style="height: 615px; display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <div class="w-24 h-24 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full mx-auto mb-6 flex items-center justify-center">
                        <span class="text-3xl font-bold text-white">{{ strtoupper(substr($customer->name, 0, 1)) }}</span>
                    </div>
                    <h3 class="text-xl font-bold text-blue-300 mb-2">{{ $customer->name }}</h3>
                    <p class="text-gray-400 mb-6">{{ $customer->email }}</p>
                </div>
                
                <div class="space-y-4">
                    <div class="bg-white/5 rounded-lg p-4">
                        <div class="text-sm text-gray-400 mb-1">Customer ID</div>
                        <div class="text-blue-200 font-semibold">#{{ $customer->id }}</div>
                    </div>
                    <div class="bg-white/5 rounded-lg p-4">
                        <div class="text-sm text-gray-400 mb-1">Member Since</div>
                        <div class="text-blue-200 font-semibold">{{ $customer->created_at->format('M d, Y') }}</div>
                    </div>
                    <div class="bg-white/5 rounded-lg p-4">
                        <div class="text-sm text-gray-400 mb-1">Last Activity</div>
                        <div class="text-blue-200 font-semibold">{{ $customer->updated_at->diffForHumans() }}</div>
                    </div>
                    <div class="bg-white/5 rounded-lg p-4">
                        <div class="text-sm text-gray-400 mb-2">Status</div>
                        <span class="inline-block bg-green-600/20 text-green-300 px-3 py-1 rounded-full text-xs font-semibold">
                            Active
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="lg:col-span-1">
            <div class="grid grid-cols-1 gap-6">
                <div class="bg-white/10 rounded-xl p-8 text-center border border-white/10" style="height: 135px;">
                    <div class="text-2xl font-bold text-blue-300 mb-2">{{ $customer->orders->count() }}</div>
                    <div class="text-sm text-gray-400">Total Orders</div>
                </div>
                <div class="bg-white/10 rounded-xl p-8 text-center border border-white/10" style="height: 135px;">
                    <div class="text-2xl font-bold text-blue-300 mb-2">${{ number_format($customer->orders->sum('total'), 0) }}</div>
                    <div class="text-sm text-gray-400">Total Spent</div>
                </div>
                <div class="bg-white/10 rounded-xl p-8 text-center border border-white/10" style="height: 135px;">
                    <div class="text-2xl font-bold text-blue-300 mb-2">
                        ${{ $customer->orders->count() > 0 ? number_format($customer->orders->avg('total'), 0) : '0' }}
                    </div>
                    <div class="text-sm text-gray-400">Avg Order</div>
                </div>
                <div class="bg-white/10 rounded-xl p-8 text-center border border-white/10" style="height: 135px;">
                    <div class="text-2xl font-bold text-blue-300 mb-2">
                        {{ $customer->orders->first() ? $customer->orders->first()->created_at->diffForHumans() : 'Never' }}
                    </div>
                    <div class="text-sm text-gray-400">Last Order</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Full Width Order History Table -->
    <div class="bg-white/10 rounded-xl p-6 shadow border border-white/10">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-blue-300">Order History</h3>
            @if($customer->orders->count() > 10)
                <a href="#" class="text-blue-400 hover:text-blue-300 text-sm">View All Orders</a>
            @endif
        </div>
        
        @if($customer->orders->count() > 0)
            <div class="overflow-x-auto rounded-lg bg-white/5">
                <table class="w-full text-left min-w-full">
                    <thead class="text-blue-300 border-b border-white/10 bg-black/20">
                        <tr>
                            <th class="px-6 py-4 text-sm font-semibold">Order ID</th>
                            <th class="px-6 py-4 text-sm font-semibold">Date</th>
                            <th class="px-6 py-4 text-sm font-semibold">Status</th>
                            <th class="px-6 py-4 text-sm font-semibold">Total</th>
                            <th class="px-6 py-4 text-sm font-semibold">Items</th>
                            <th class="px-6 py-4 text-sm font-semibold">Payment Method</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customer->orders->take(10) as $order)
                            <tr class="border-b border-white/5 hover:bg-blue-900/10 transition-colors">
                                <td class="px-6 py-4 font-bold text-blue-200">#{{ $order->id }}</td>
                                <td class="px-6 py-4 text-gray-300">
                                    <div>{{ $order->created_at->format('M d, Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $order->created_at->format('h:i A') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                                        {{ $order->status === 'completed' ? 'bg-green-600/20 text-green-300' : 
                                           ($order->status === 'pending' ? 'bg-yellow-600/20 text-yellow-300' : 
                                           ($order->status === 'processing' ? 'bg-blue-600/20 text-blue-300' : 
                                           ($order->status === 'cancelled' ? 'bg-red-600/20 text-red-300' : 'bg-gray-600/20 text-gray-300'))) }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-semibold text-blue-200">${{ number_format($order->total, 2) }}</td>
                                <td class="px-6 py-4 text-gray-300">
                                    <div class="text-sm">{{ $order->items->count() }} item(s)</div>
                                    <div class="text-xs text-gray-500">
                                        @if($order->items->count() > 0)
                                            {{ $order->items->first()->product->name ?? 'Product' }}
                                            @if($order->items->count() > 1)
                                                + {{ $order->items->count() - 1 }} more
                                            @endif
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-300">
                                    <div class="text-sm">{{ ucfirst($order->payment_method ?? 'N/A') }}</div>
                                    <div class="text-xs text-gray-500">
                                        {{ $order->payment_status ? ucfirst($order->payment_status) : 'Unknown' }}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($customer->orders->count() > 10)
                <div class="mt-4 text-center">
                    <a href="#" class="bg-blue-600/20 hover:bg-blue-600/30 text-blue-300 px-4 py-2 rounded-lg transition-colors text-sm">
                        Load More Orders
                    </a>
                </div>
            @endif
        @else
            <div class="text-center text-gray-400 py-16">
                <div class="w-20 h-20 bg-gray-600/20 rounded-full mx-auto mb-6 flex items-center justify-center">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
                <p class="text-xl font-semibold mb-2">No orders yet</p>
                <p class="text-sm">This customer hasn't placed any orders.</p>
            </div>
        @endif
    </div>
</div>
@endsection