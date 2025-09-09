@extends('layouts.admin')
@section('title','Categories')
@section('content')
<div class="flex items-center justify-between mb-4">
  <h1 class="text-2xl font-bold">Categories</h1>
  <a href="{{ route('admin.categories.create') }}" class="px-4 py-2 rounded bg-blue-600 hover:bg-blue-500">New Category</a>
</div>

<div class="rounded-2xl bg-white/5 border border-white/10 overflow-hidden">
  <table class="min-w-full text-sm">
    <thead class="bg-white/10">
      <tr>
        <th class="text-left px-4 py-3">Name</th>
        <th class="text-left px-4 py-3">Slug</th>
        <th class="px-4 py-3">Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach($categories as $c)
      <tr class="border-t border-white/10">
        <td class="px-4 py-3">{{ $c->name }}</td>
        <td class="px-4 py-3 text-gray-300">{{ $c->slug }}</td>
        <td class="px-4 py-3">
          <a href="{{ route('admin.categories.show', $c) }}" class="text-blue-400 hover:underline mr-2">View</a>
          <a href="{{ route('admin.categories.edit', $c) }}" class="text-blue-400 hover:underline mr-2">Edit</a>
          <form action="{{ route('admin.categories.destroy', $c) }}" method="POST" class="inline">
            @csrf @method('DELETE')
            <button class="text-red-400 hover:underline" onclick="return confirm('Delete this category?')">Delete</button>
          </form>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>

<div class="mt-4">{{ $categories->links() }}</div>
@endsection
