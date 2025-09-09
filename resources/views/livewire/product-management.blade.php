<div>
    <!-- Product Management Component -->
    <h2>Product Management</h2>
    <ul>
        @foreach($products as $product)
            <li>{{ $product->name }} - ${{ $product->price }}</li>
        @endforeach
    </ul>
</div>
