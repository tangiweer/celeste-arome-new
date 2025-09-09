<div>
    <!-- Coupon Discount Component -->
    <h3>Apply Coupon</h3>
    <form wire:submit.prevent="applyCoupon">
        <input type="text" wire:model.defer="couponCode" placeholder="Enter coupon code" class="input input-bordered" />
        <button type="submit" class="btn btn-primary mt-2">Apply</button>
    </form>
    @if($discount)
        <div class="mt-2 text-success">Coupon applied! Discount: ${{ $discount }}</div>
    @elseif($error)
        <div class="mt-2 text-error">{{ $error }}</div>
    @endif
</div>
