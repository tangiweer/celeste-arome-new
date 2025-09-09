<div>
    <!-- Order Tracking Component -->
    <h2>Track Your Order</h2>
    <form wire:submit.prevent="trackOrder">
        <input type="text" wire:model.defer="orderNumber" placeholder="Order Number" class="input input-bordered" />
        <button type="submit" class="btn btn-primary mt-2">Track</button>
    </form>
    @if($order)
        <div class="mt-2">Order Status: {{ $order->status }}</div>
    @endif
</div>
