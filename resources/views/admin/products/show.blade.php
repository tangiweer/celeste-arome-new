@extends('layouts.admin')

@section('title', 'View Product • Céleste Arôme')

@section('content')
<div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
    <h1 class="text-3xl font-extrabold text-blue-300 tracking-tight">Product Details</h1>
    <a href="{{ route('admin.products.index') }}" class="bg-white/10 hover:bg-white/20 text-blue-300 px-4 py-2 rounded-lg transition-colors border border-white/10">
        Back to Products
    </a>
</div>

<div class="bg-white/10 rounded-xl p-6 shadow">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Product Image -->
        <div class="flex justify-center">
            @if($product->images && $product->images->count() > 0)
                @php
                    $primaryImage = $product->images->where('is_primary', true)->first() ?? $product->images->first();
                @endphp
                <img src="{{ asset('storage/' . $primaryImage->path) }}" 
                     alt="{{ $product->name }}" 
                     class="w-full max-w-md h-auto object-cover rounded-lg shadow-lg">
            @else
                <div class="w-full max-w-md h-64 bg-gray-600 rounded-lg flex items-center justify-center">
                    <span class="text-gray-400 text-lg">No Image</span>
                </div>
            @endif
        </div>

        <!-- Product Details -->
        <div class="space-y-4">
            <div>
                <label class="text-sm text-gray-400">Product Name</label>
                <p class="text-xl font-bold text-blue-300">{{ $product->name }}</p>
            </div>

            <div>
                <label class="text-sm text-gray-400">Category</label>
                <p class="text-lg text-blue-300">{{ $product->category->name ?? 'No Category' }}</p>
            </div>

            @if($product->brand)
            <div>
                <label class="text-sm text-gray-400">Brand</label>
                <p class="text-lg text-blue-300">{{ $product->brand }}</p>
            </div>
            @endif

            <div>
                <label class="text-sm text-gray-400">Price</label>
                <p class="text-lg text-blue-300 font-bold">${{ number_format($product->price, 2) }}</p>
            </div>

            <div>
                <label class="text-sm text-gray-400">Stock</label>
                <p class="text-lg text-blue-300">{{ $product->stock }} units</p>
            </div>

            <div>
                <label class="text-sm text-gray-400">Status</label>
                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>

            <div>
                <label class="text-sm text-gray-400">Slug</label>
                <p class="text-lg text-blue-300">{{ $product->slug }}</p>
            </div>

            @if($product->description)
            <div>
                <label class="text-sm text-gray-400">Description</label>
                <p class="text-blue-300 mt-2">{{ $product->description }}</p>
            </div>
            @endif

            <div class="pt-4 border-t border-white/10">
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <label class="text-gray-400">Created</label>
                        <p class="text-blue-300">{{ $product->created_at->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <label class="text-gray-400">Updated</label>
                        <p class="text-blue-300">{{ $product->updated_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4 pt-4">
                <a href="{{ route('admin.products.edit', $product) }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition-colors">
                    Edit Product
                </a>
                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline">
                    @csrf 
                    @method('DELETE')
                    <button type="submit" 
                            class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-semibold transition-colors"
                            onclick="return confirm('Are you sure you want to delete this product?')">
                        Delete Product
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Additional Images (if any) -->
    @if($product->images && $product->images->count() > 1)
    <div class="mt-8 pt-8 border-t border-white/10">
        <h3 class="text-lg font-semibold text-blue-300 mb-4">Additional Images</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($product->images->where('is_primary', false) as $image)
                <img src="{{ asset('storage/' . $image->path) }}" 
                     alt="{{ $product->name }}" 
                     class="w-full h-24 object-cover rounded-lg">
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection