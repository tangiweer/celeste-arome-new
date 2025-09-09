<div>
    @forelse ($orders as $order)
        <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-xl p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="text-white font-semibold">#{{ $order->id }}</div>
                    <div class="text-gray-400 text-sm">{{ $order->created_at->format('d M Y') }}</div>
                </div>
                <div class="text-white font-semibold">${{ number_format($order->total, 2) }}</div>
                <div>
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                        {{
                            match($order->status){
                                'delivered' => 'bg-green-500/20 text-green-400',
                                'shipped','out_for_delivery' => 'bg-orange-500/20 text-orange-400',
                                'cancelled' => 'bg-red-500/20 text-red-400',
                                default => 'bg-gray-500/20 text-gray-300'
                            }
                        }}">
                        {{ ucfirst(str_replace('_',' ',$order->status)) }}
                    </span>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-16 text-gray-300">No orders yet.</div>
    @endforelse

    <div class="mt-6">
        {{ $orders->links() }}
    </div>
</div>
