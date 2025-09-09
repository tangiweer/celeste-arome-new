<div>
    <!-- Product Recommendation Component -->
    <h3>Recommended Products</h3>
    <ul>
        @foreach($recommendations as $product)
            <li>{{ $product->name }} - ${{ $product->price }}</li>
        @endforeach
    </ul>
</div>
