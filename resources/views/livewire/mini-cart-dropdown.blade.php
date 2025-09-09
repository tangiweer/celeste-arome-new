<div>
    <!-- Mini Cart Dropdown Component -->
    <button wire:click="toggleDropdown" class="btn btn-ghost">
        <i class="fa fa-shopping-cart"></i>
        <span class="badge">{{ $cartCount }}</span>
    </button>
    @if($showDropdown)
        <div class="dropdown-content">
            @foreach($cartItems as $item)
                <div>{{ $item['name'] }} x{{ $item['quantity'] }}</div>
            @endforeach
            <a href="{{ route('cart') }}" class="btn btn-primary mt-2">View Cart</a>
        </div>
    @endif
</div>
