<div>
    <!-- Newsletter Signup Component -->
    <h3>Subscribe to our Newsletter</h3>
    <form wire:submit.prevent="subscribe">
        <input type="email" wire:model.defer="email" placeholder="Your email" class="input input-bordered" />
        <button type="submit" class="btn btn-primary mt-2">Subscribe</button>
    </form>
    @if($success)
        <div class="mt-2 text-success">{{ $success }}</div>
    @endif
</div>
