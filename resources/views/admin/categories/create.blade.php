
@extends('layouts.admin')
@section('title','New Category')
@section('content')
<h1 class="text-2xl font-bold mb-4">New Category</h1>
<form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-4 max-w-lg">
  @csrf
  <div>
    <label class="block text-sm mb-1">Name</label>
    <input name="name" value="{{ old('name') }}" class="w-full px-3 py-2 rounded bg-black/40 border border-white/10">
  </div>
  <div>
    <label class="block text-sm mb-1">Slug (optional)</label>
    <input name="slug" value="{{ old('slug') }}" class="w-full px-3 py-2 rounded bg-black/40 border border-white/10">
  </div>
  <div class="flex gap-2">
    <button class="px-4 py-2 rounded bg-emerald-600 hover:bg-emerald-500">Save</button>
    <a href="{{ route('admin.categories.index') }}" class="px-4 py-2 rounded bg-white/10">Cancel</a>
  </div>
</form>
@endsection
