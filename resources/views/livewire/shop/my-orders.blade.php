<div class="overflow-hidden rounded-2xl bg-white/5 backdrop-blur border border-white/10">
    <table class="w-full text-left">
        <thead class="text-gray-300 border-b border-white/10">
            <tr>
                <th class="px-4 py-3">Order #</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3">Subtotal</th>
                <th class="px-4 py-3">Total</th>
                <th class="px-4 py-3">Date</th>
            </tr>
        </thead>
        <tbody>
        @forelse($orders as $order)
            <tr class="border-b border-white/5 hover:bg-white/5">
                <td class="px-4 py-3">{{ $order->id }}</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 text-xs rounded bg-white/10 border border-white/10">{{ ucfirst($order->status) }}</span>
                </td>
                <td class="px-4 py-3">${{ number_format($order->subtotal,2) }}</td>
                <td class="px-4 py-3 font-semibold">${{ number_format($order->total,2) }}</td>
                <td class="px-4 py-3 text-gray-400">{{ $order->created_at->format('Y-m-d H:i') }}</td>
            </tr>
        @empty
            <tr><td class="px-4 py-6 text-center text-gray-400" colspan="5">No orders yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
