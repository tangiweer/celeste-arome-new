<div>
    <h2 class="text-xl font-bold text-blue-300 mb-4">Orders</h2>
    <table class="w-full text-left bg-white/10 rounded-lg">
        <thead>
            <tr class="text-blue-300">
                <th class="px-4 py-2">Order #</th>
                <th class="px-4 py-2">Customer</th>
                <th class="px-4 py-2">Total</th>
                <th class="px-4 py-2">Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr class="border-b border-white/10">
                <td class="px-4 py-2">{{ $order->id }}</td>
                <td class="px-4 py-2">{{ $order->user->name ?? 'N/A' }}</td>
                <td class="px-4 py-2">${{ number_format($order->total,2) }}</td>
                <td class="px-4 py-2">{{ $order->created_at->format('Y-m-d') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
