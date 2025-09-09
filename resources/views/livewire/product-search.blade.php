<div>
    <!-- Product Search Component -->
    <input type="text" wire:model.debounce.300ms="search" placeholder="Search products..." class="input input-bordered" />
    <ul>
        @foreach($results as $product)
            <li>{{ $product->name }} - ${{ $product->price }}</li>
        @endforeach
    </ul>
</div>
