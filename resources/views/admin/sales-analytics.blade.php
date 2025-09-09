@extends('layouts.admin')
@section('title','Sales Analytics')
@section('content')
<div class="flex items-center justify-between mb-8">
    <h1 class="text-2xl font-extrabold text-blue-300">Sales Analytics</h1>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <div class="bg-white/10 rounded-xl p-6 shadow">
        <div class="text-lg font-bold text-blue-300 mb-4">Revenue Over Time</div>
        <div class="h-48 flex items-center justify-center text-gray-400">[Chart]</div>
    </div>
    <div class="bg-white/10 rounded-xl p-6 shadow">
        <div class="text-lg font-bold text-blue-300 mb-4">Best Sellers</div>
        <ul>
            @foreach($bestSellers as $product)
                <li class="flex justify-between py-2 border-b border-white/10">
                    <span>{{ $product->name }}</span>
                    <span>${{ number_format($product->revenue,2) }}</span>
                </li>
            @endforeach
        </ul>
    </div>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <div class="bg-white/10 rounded-xl p-6 shadow">
        <div class="text-lg font-bold text-blue-300 mb-4">Inventory Status</div>
        <ul>
            @foreach($inventory as $item)
                <li class="flex justify-between py-2 border-b border-white/10">
                    <span>{{ $item->name }}</span>
                    <span>{{ $item->stock }}</span>
                </li>
            @endforeach
        </ul>
    </div>
    <div class="bg-white/10 rounded-xl p-6 shadow">
        <div class="text-lg font-bold text-blue-300 mb-4">Recent Orders</div>
        <ul>
            @foreach($recentOrders as $order)
                <li class="flex justify-between py-2 border-b border-white/10">
                    <span>Order #{{ $order->id }}</span>
                    <span>${{ number_format($order->total,2) }}</span>
                </li>
            @endforeach
        </ul>
    </div>
</div>
@endsection
