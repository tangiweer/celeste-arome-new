@extends('layouts.admin')

@section('title', 'Admin • Céleste Arôme')
@section('content')
<div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
    <h1 class="text-3xl font-extrabold text-blue-300 tracking-tight">Dashboard</h1>
    <input type="text" placeholder="Search..." class="rounded-lg bg-black/10 border border-white/10 px-4 py-2 text-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-500 w-full md:w-64">
</div>
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white/10 rounded-xl p-6 shadow flex flex-col gap-2">
        <div class="text-xs text-gray-400">Total Revenue</div>
        <div class="text-3xl font-bold text-blue-300">${{ $stats['revenue'] ?? '0' }}</div>
        <div class="text-xs text-gray-400">Last 30 days</div>
    </div>
    <div class="bg-white/10 rounded-xl p-6 shadow flex flex-col gap-2">
        <div class="text-xs text-gray-400">Total Orders</div>
        <div class="text-3xl font-bold text-blue-300">{{ $stats['orders'] ?? '0' }}</div>
        <div class="text-xs text-gray-400">Last 30 days</div>
    </div>
    <div class="bg-white/10 rounded-xl p-6 shadow flex flex-col gap-2">
        <div class="text-xs text-gray-400">Total Customers</div>
        <div class="text-3xl font-bold text-blue-300">{{ $stats['customers'] ?? '0' }}</div>
        <div class="text-xs text-gray-400">Last 30 days</div>
    </div>
    <div class="bg-white/10 rounded-xl p-6 shadow flex flex-col gap-2">
        <div class="text-xs text-gray-400">Pending Deliveries</div>
        <div class="text-3xl font-bold text-blue-300">{{ $stats['pending_deliveries'] ?? '0' }}</div>
        <div class="text-xs text-gray-400">Last 30 days</div>
    </div>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white/10 rounded-xl p-6 shadow col-span-2">
        <div class="flex items-center justify-between mb-4">
            <div class="text-lg font-bold text-blue-300">Sales Analytics</div>
            <select class="rounded bg-black/10 border border-white/10 px-2 py-1 text-blue-300">
                <option>Jul 2023</option>
                <option>Aug 2023</option>
                <option>Sep 2023</option>
            </select>
        </div>
        <div class="h-40 relative">
            <canvas id="salesChart"></canvas>
        </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
        <script>
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                datasets: [{
                    label: 'Revenue',
                    data: [{{ $stats['revenue'] * 0.2 }}, {{ $stats['revenue'] * 0.4 }}, {{ $stats['revenue'] * 0.7 }}, {{ $stats['revenue'] }}],
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: {
                            color: '#93c5fd'
                        }
                    }
                },
                scales: {
                    y: {
                        ticks: {
                            color: '#6b7280'
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        }
                    },
                    x: {
                        ticks: {
                            color: '#6b7280'
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        }
                    }
                }
            }
        });
        </script>
        
        <div class="flex justify-between mt-4 gap-8">
            <div>
                <div class="text-xs text-gray-400">Income</div>
                <div class="text-lg font-bold text-blue-300">{{ $stats['income'] ?? '0' }}</div>
            </div>
            <div>
                <div class="text-xs text-gray-400">Expenses</div>
                <div class="text-lg font-bold text-blue-300">{{ $stats['expenses'] ?? '0' }}</div>
            </div>
            <div>
                <div class="text-xs text-gray-400">Balance</div>
                <div class="text-lg font-bold text-blue-300">{{ $stats['balance'] ?? '0' }}</div>
            </div>
        </div>
    </div>
    <div class="bg-white/10 rounded-xl p-6 shadow flex flex-col items-center justify-center">
        <div class="text-lg font-bold text-blue-300 mb-2">Sales Target</div>
        <div class="w-24 h-24 flex items-center justify-center text-blue-300">[600/18000]</div>
    </div>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <div class="bg-white/10 rounded-xl p-6 shadow">
        <div class="text-lg font-bold text-blue-300 mb-4">Top Selling Perfumes</div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($topProducts as $product)
                <div class="flex flex-col items-center">
                    @if($product->images && $product->images->count() > 0)
                        @php
                            $primaryImage = $product->images->where('is_primary', true)->first() ?? $product->images->first();
                        @endphp
                        <img src="{{ asset('storage/' . $primaryImage->path) }}" 
                             alt="{{ $product->name }}" 
                             class="w-16 h-16 object-cover rounded mb-2">
                    @else
                        <div class="w-16 h-16 bg-gray-600 rounded mb-2 flex items-center justify-center">
                            <span class="text-xs text-gray-400">No Image</span>
                        </div>
                    @endif
                    <div class="text-sm text-blue-300 font-semibold text-center">{{ Str::limit($product->name, 20) }}</div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="bg-white/10 rounded-xl p-6 shadow">
        <div class="text-lg font-bold text-blue-300 mb-4">Current Offers</div>
        <div class="flex flex-col gap-2">
            @foreach($offers as $offer)
                <div class="bg-white/20 rounded px-4 py-2 text-blue-400 font-semibold flex justify-between items-center">
                    <span>{{ $offer['title'] }}</span>
                    <span class="text-xs text-gray-400">{{ $offer['date'] }}</span>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection