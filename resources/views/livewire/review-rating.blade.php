<div>
    <!-- Review & Rating Component -->
    <h3>Leave a Review</h3>
    <form wire:submit.prevent="submitReview">
        <textarea wire:model.defer="review" placeholder="Your review" class="textarea textarea-bordered"></textarea>
        <input type="number" wire:model.defer="rating" min="1" max="5" class="input input-bordered mt-2" placeholder="Rating (1-5)" />
        <button type="submit" class="btn btn-primary mt-2">Submit</button>
    </form>
    @if($success)
        <div class="mt-2 text-success">{{ $success }}</div>
    @endif
</div>
