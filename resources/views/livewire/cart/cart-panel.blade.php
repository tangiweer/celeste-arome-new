<div>
	<h3>Your Cart</h3>
	<ul>
		@forelse($cartItems as $item)
			<li>
				{{ $item->product->name ?? 'Product' }} (x{{ $item->qty }}) - ${{ $item->unit_price }}
				<button wire:click="removeItem({{ $item->id }})">Remove</button>
			</li>
		@empty
			<li>Your cart is empty.</li>
		@endforelse
	</ul>
</div>

