@extends('layouts.admin')
@section('title','New Product')
@section('page-title','Create Product')

@section('content')
<form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data"
      class="max-w-3xl space-y-6 bg-white/5 backdrop-blur border border-white/10 p-6 rounded-2xl">
    @csrf

    <div>
        <label class="block text-sm text-gray-300 mb-1">Name</label>
        <input name="name" value="{{ old('name') }}" class="w-full px-4 py-2 rounded-lg bg-black/40 border border-white/10" required>
        @error('name') <p class="text-rose-400 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="grid md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm text-gray-300 mb-1">Category</label>
            <select name="category_id" class="w-full px-4 py-2 rounded-lg bg-black/40 border border-white/10" required>
                <option value="">Choose…</option>
                @foreach($categories as $c)
                    <option value="{{ $c->id }}" @selected(old('category_id')==$c->id)>{{ $c->name }}</option>
                @endforeach
            </select>
            @error('category_id') <p class="text-rose-400 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm text-gray-300 mb-1">Brand</label>
            <input name="brand" value="{{ old('brand') }}" class="w-full px-4 py-2 rounded-lg bg-black/40 border border-white/10">
            @error('brand') <p class="text-rose-400 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="grid md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm text-gray-300 mb-1">Price ($)</label>
            <input type="number" step="0.01" min="0" name="price" value="{{ old('price') }}"
                   class="w-full px-4 py-2 rounded-lg bg-black/40 border border-white/10" required>
            @error('price') <p class="text-rose-400 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm text-gray-300 mb-1">Stock</label>
            <input type="number" min="0" name="stock" value="{{ old('stock',0) }}"
                   class="w-full px-4 py-2 rounded-lg bg-black/40 border border-white/10" required>
            @error('stock') <p class="text-rose-400 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="flex items-center gap-3 mt-6">
            <input id="is_active" type="checkbox" name="is_active" value="1" class="h-5 w-5" checked>
            <label for="is_active" class="text-sm text-gray-300">Active</label>
        </div>
    </div>

    <div>
        <label class="block text-sm text-gray-300 mb-1">Description</label>
        <textarea name="description" rows="5" class="w-full px-4 py-2 rounded-lg bg-black/40 border border-white/10">{{ old('description') }}</textarea>
        @error('description') <p class="text-rose-400 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm text-gray-300 mb-1">Primary Image</label>
        <input type="file" name="image" class="block w-full text-sm file:mr-4 file:rounded-lg file:border-0 file:bg-blue-600 file:px-4 file:py-2 hover:file:bg-blue-500">
        @error('image') <p class="text-rose-400 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="flex gap-3">
        <button class="px-5 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-500">Save</button>
        <a href="{{ route('admin.products.index') }}" class="px-5 py-2 rounded-lg bg-white/10 hover:bg-white/20">Cancel</a>
    </div>
</form>
@endsection
