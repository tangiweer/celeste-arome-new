<div>
    <!-- Order Management Component -->
    <h2>Order Management</h2>
    <ul>
        @foreach($orders as $order)
            <li>Order #{{ $order->id }} - ${{ $order->total }} - {{ $order->status }}</li>
        @endforeach
    </ul>
</div>
