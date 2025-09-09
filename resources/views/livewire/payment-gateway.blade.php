<div>
    <!-- Payment Gateway Component -->
    <h2>Payment</h2>
    <form wire:submit.prevent="pay">
        <input type="text" wire:model.defer="cardNumber" placeholder="Card Number" class="input input-bordered" />
        <input type="text" wire:model.defer="expiry" placeholder="MM/YY" class="input input-bordered mt-2" />
        <input type="text" wire:model.defer="cvc" placeholder="CVC" class="input input-bordered mt-2" />
        <button type="submit" class="btn btn-primary mt-2">Pay</button>
    </form>
    @if($success)
        <div class="mt-2 text-success">Payment successful!</div>
    @elseif($error)
        <div class="mt-2 text-error">{{ $error }}</div>
    @endif
</div>
