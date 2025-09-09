@extends('layouts.admin')
@section('title','Products')
@section('content')
<div class="flex items-center justify-between mb-8">
    <h1 class="text-2xl font-extrabold text-blue-300">Products</h1>
    <a href="{{ route('admin.products.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg font-semibold hover:bg-blue-600 transition">Add Product</a>
</div>
<div class="overflow-hidden rounded-2xl bg-white/5 backdrop-blur border border-white/10 shadow-lg">
    <table class="w-full text-left">
        <thead class="text-blue-300 border-b border-white/10 bg-black/20">
            <tr>
                <th class="px-4 py-3">#</th>
                <th class="px-4 py-3">Name</th>
                <th class="px-4 py-3">Category</th>
                <th class="px-4 py-3">Price</th>
                <th class="px-4 py-3">Stock</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3">Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($products as $product)
            <tr class="border-b border-white/5 hover:bg-blue-900/10">
                <td class="px-4 py-3 font-bold text-blue-200">{{ $product->id }}</td>
                <td class="px-4 py-3">{{ $product->name }}</td>
                <td class="px-4 py-3">{{ $product->category->name ?? '—' }}</td>
                <td class="px-4 py-3">${{ number_format($product->price,2) }}</td>
                <td class="px-4 py-3">{{ $product->stock }}</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 text-xs rounded bg-blue-900/20 border border-blue-900/20 text-blue-300">{{ $product->is_active ? 'Active' : 'Inactive' }}</span>
                </td>
                <td class="px-4 py-3">
                    <a href="{{ route('admin.products.show', $product) }}" class="text-blue-400 hover:underline mr-2">View</a>
                    <a href="{{ route('admin.products.edit', $product) }}" class="text-blue-400 hover:underline mr-2">Edit</a>
                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline">
                        @csrf @method('DELETE')
                        <button class="text-red-400 hover:underline" onclick="return confirm('Delete this product?')">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td class="px-4 py-6 text-center text-gray-400" colspan="7">No products found.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="mt-6">{{ $products->links() }}</div>
@endsection
