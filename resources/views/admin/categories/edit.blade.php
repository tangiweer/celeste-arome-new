
@extends('layouts.admin')
@section('title','Edit Category')
@section('content')
<h1 class="text-2xl font-bold mb-4">Edit Category</h1>
<form method="POST" action="{{ route('admin.categories.update',$category) }}" class="space-y-4 max-w-lg">
  @csrf @method('PUT')
  <div>
    <label class="block text-sm mb-1">Name</label>
    <input name="name" value="{{ old('name',$category->name) }}" class="w-full px-3 py-2 rounded bg-black/40 border border-white/10">
  </div>
  <div>
    <label class="block text-sm mb-1">Slug (optional)</label>
    <input name="slug" value="{{ old('slug',$category->slug) }}" class="w-full px-3 py-2 rounded bg-black/40 border border-white/10">
  </div>
  <div class="flex gap-2">
    <button class="px-4 py-2 rounded bg-emerald-600 hover:bg-emerald-500">Update</button>
    <a href="{{ route('admin.categories.index') }}" class="px-4 py-2 rounded bg-white/10">Cancel</a>
  </div>
</form>
@endsection
