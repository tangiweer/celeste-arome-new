@extends('layouts.admin')

@section('title', 'Edit Product')

@section('content')
<div class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-2xl">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-2xl font-extrabold text-blue-300">Edit Product</h1>
            <a href="{{ route('admin.products.show', $product) }}" class="bg-white/10 hover:bg-white/20 text-blue-300 px-4 py-2 rounded-lg transition-colors border border-white/10">
                Back to Product
            </a>
        </div>

        <div class="bg-white/10 rounded-xl p-6 shadow border border-white/10">
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-blue-300 mb-2">Product Name</label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           value="{{ old('name', $product->name) }}"
                           class="w-full rounded-lg bg-black/10 border border-white/10 px-4 py-3 text-blue-200 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Enter product name"
                           required>
                    @error('name')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category and Brand -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-blue-300 mb-2">Category</label>
                        <select name="category_id" 
                                id="category_id"
                                class="w-full rounded-lg bg-black/10 border border-white/10 px-4 py-3 text-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                required>
                            @foreach($categories as $c)
                                <option value="{{ $c->id }}" @selected(old('category_id', $product->category_id) == $c->id)>{{ $c->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="brand" class="block text-sm font-medium text-blue-300 mb-2">Brand</label>
                        <input type="text" 
                               name="brand" 
                               id="brand" 
                               value="{{ old('brand', $product->brand) }}"
                               class="w-full rounded-lg bg-black/10 border border-white/10 px-4 py-3 text-blue-200 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Enter brand name">
                        @error('brand')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Price and Stock -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="price" class="block text-sm font-medium text-blue-300 mb-2">Price ($)</label>
                        <input type="number" 
                               step="0.01" 
                               min="0" 
                               name="price" 
                               id="price"
                               value="{{ old('price', $product->price) }}"
                               class="w-full rounded-lg bg-black/10 border border-white/10 px-4 py-3 text-blue-200 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="0.00"
                               required>
                        @error('price')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="stock" class="block text-sm font-medium text-blue-300 mb-2">Stock Quantity</label>
                        <input type="number" 
                               min="0" 
                               name="stock" 
                               id="stock"
                               value="{{ old('stock', $product->stock) }}"
                               class="w-full rounded-lg bg-black/10 border border-white/10 px-4 py-3 text-blue-200 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="0"
                               required>
                        @error('stock')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-blue-300 mb-2">Description</label>
                    <textarea name="description" 
                              id="description"
                              rows="4"
                              class="w-full rounded-lg bg-black/10 border border-white/10 px-4 py-3 text-blue-200 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Enter product description">{{ old('description', $product->description) }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Image Upload -->
                <div>
                    <label for="image" class="block text-sm font-medium text-blue-300 mb-2">Product Image</label>
                    
                    <!-- Current Image Display -->
                    @if($product->images && $product->images->count() > 0)
                        @php
                            $primaryImage = $product->images->where('is_primary', true)->first() ?? $product->images->first();
                        @endphp
                        <div class="mb-4 p-4 bg-black/20 rounded-lg border border-white/5">
                            <p class="text-xs text-gray-400 mb-2">Current image:</p>
                            <div class="flex items-center gap-4">
                                <img src="{{ asset('storage/' . $primaryImage->path) }}" 
                                     alt="Current product image" 
                                     class="w-20 h-20 object-cover rounded-lg">
                                <div class="flex-1">
                                    <p class="text-sm text-blue-200">{{ basename($primaryImage->path) }}</p>
                                    <p class="text-xs text-gray-400">Upload a new image to replace this one</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <!-- File Input -->
                    <input type="file" 
                           name="image" 
                           id="image"
                           accept="image/*"
                           class="w-full text-sm text-blue-200 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-600 file:text-white hover:file:bg-blue-700 file:transition-colors">
                    
                    @if(!$product->images || $product->images->count() === 0)
                        <p class="text-xs text-gray-400 mt-2">No current image. Upload an image for this product.</p>
                    @endif
                    
                    @error('image')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div class="flex items-center gap-3">
                    <input type="checkbox" 
                           name="is_active" 
                           id="is_active"
                           value="1" 
                           class="h-5 w-5 text-blue-600 rounded border-white/10 bg-black/10 focus:ring-blue-500 focus:ring-2"
                           @checked(old('is_active', $product->is_active))>
                    <label for="is_active" class="text-sm font-medium text-blue-300">Product is Active</label>
                </div>

                <!-- Product Info Display -->
                <div class="bg-white/5 rounded-lg p-4 border border-white/5">
                    <h3 class="text-sm font-medium text-blue-300 mb-3">Product Information</h3>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-400">Product ID:</span>
                            <span class="text-blue-200 font-semibold ml-2">#{{ $product->id }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400">Slug:</span>
                            <span class="text-blue-200 font-semibold ml-2">{{ $product->slug }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400">Created:</span>
                            <span class="text-blue-200 font-semibold ml-2">{{ $product->created_at->format('M d, Y') }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400">Last Updated:</span>
                            <span class="text-blue-200 font-semibold ml-2">{{ $product->updated_at->format('M d, Y') }}</span>
                        </div>
                        <div class="col-span-2">
                            <span class="text-gray-400">Images:</span>
                            <span class="text-blue-200 font-semibold ml-2">{{ $product->images ? $product->images->count() : 0 }} uploaded</span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-4 pt-6 border-t border-white/10">
                    <button type="submit" 
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Update Product
                    </button>
                    <a href="{{ route('admin.products.show', $product) }}" 
                       class="flex-1 text-center bg-white/10 hover:bg-white/20 text-blue-300 px-6 py-3 rounded-lg font-semibold transition-colors border border-white/10">
                        Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

@if(session('success'))
    <div class="fixed top-4 right-4 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg z-50">
        {{ session('success') }}
    </div>
@endif

@endsection