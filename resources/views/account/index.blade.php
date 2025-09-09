@extends('layouts.app')
@section('title', 'My Account')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-7xl">

  @if (session('success'))
    <div class="mb-6 p-3 rounded bg-green-600/20 border border-green-600/30 text-green-300">
      {{ session('success') }}
    </div>
  @endif
  @if (session('error'))
    <div class="mb-6 p-3 rounded bg-rose-600/20 border border-rose-600/30 text-rose-300">
      {{ session('error') }}
    </div>
  @endif

  <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
    {{-- Sidebar --}}
    <div class="lg:col-span-1">
      <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-6 sticky top-6">
        <h2 class="text-2xl font-bold text-white mb-2">My account</h2>
        <p class="text-blue-300 mb-8">Welcome, {{ auth()->user()->name }}</p>

        @php $s = $section ?? 'orders'; @endphp
        <nav class="space-y-2">

          <a href="{{ route('account.orders') }}"
             class="flex items-center gap-3 p-3 rounded-lg text-white transition-all group
             {{ $s==='orders' ? 'bg-blue-600/20 border-l-4 border-blue-600' : 'hover:bg-blue-600/10 hover:border-l-4 hover:border-blue-600' }}">
             <span class="font-medium">Orders</span>
          </a>

          <a href="{{ route('account.favorites') }}"
             class="flex items-center gap-3 p-3 rounded-lg text-white transition-all group
             {{ $s==='favorites' ? 'bg-blue-600/20 border-l-4 border-blue-600' : 'hover:bg-blue-600/10 hover:border-l-4 hover:border-blue-600' }}">
             <span class="font-medium">Wishlist</span>
          </a>

          <a href="{{ route('account.profile') }}"
             class="flex items-center gap-3 p-3 rounded-lg text-white transition-all group
             {{ $s==='profile' ? 'bg-blue-600/20 border-l-4 border-blue-600' : 'hover:bg-blue-600/10 hover:border-l-4 hover:border-blue-600' }}">
             <span class="font-medium">Personal data</span>
          </a>

          <a href="{{ route('account.password') }}"
             class="flex items-center gap-3 p-3 rounded-lg text-white transition-all group
             {{ $s==='password' ? 'bg-blue-600/20 border-l-4 border-blue-600' : 'hover:bg-blue-600/10 hover:border-l-4 hover:border-blue-600' }}">
             <span class="font-medium">Change password</span>
          </a>

          <a href="{{ route('account.addresses') }}"
             class="flex items-center gap-3 p-3 rounded-lg text-white transition-all group
             {{ $s==='addresses' ? 'bg-blue-600/20 border-l-4 border-blue-600' : 'hover:bg-blue-600/10 hover:border-l-4 hover:border-blue-600' }}">
             <span class="font-medium">Addresses</span>
          </a>

          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full mt-4 flex items-center gap-3 p-3 rounded-lg text-red-400 hover:text-red-300 transition-all group hover:bg-red-600/10">
              <span class="font-medium">Logout</span>
            </button>
          </form>
        </nav>
      </div>
    </div>

    {{-- Main content --}}
    <div class="lg:col-span-3">
      <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-8">

        @switch($s)
          {{-- Wishlist --}}
          @case('favorites')
            <h2 class="text-3xl font-bold text-white mb-8">Wishlist</h2>
            @includeIf('wishlist.index', ['wishlistItems' => $wishlistItems ?? $items ?? $wishlist ?? collect()])
            @break

          {{-- Personal data --}}
          @case('profile')
            <h2 class="text-3xl font-bold text-white mb-8">Personal data</h2>
            <div class="space-y-6 max-w-2xl">
              <div class="p-4 bg-white shadow sm:rounded-lg">
                @include('profile.partials.update-profile-information-form')
              </div>
            </div>
            @break

          {{-- Change password (+ delete account) --}}
          @case('password')
            <h2 class="text-3xl font-bold text-white mb-8">Change password</h2>
            <div class="space-y-6 max-w-2xl">
              <div class="p-4 bg-white shadow sm:rounded-lg">
                @include('profile.partials.update-password-form')
              </div>
              <div class="p-4 bg-white shadow sm:rounded-lg">
                @include('profile.partials.delete-user-form')
              </div>
            </div>
            @break

          {{-- Addresses placeholder --}}
          @case('addresses')
            <h2 class="text-3xl font-bold text-white mb-8">Addresses</h2>
            <p class="text-gray-300">Add/manage your shipping &amp; billing addresses here.</p>
            @break

          {{-- Orders (default) --}}
          @case('orders')
          @default
            <h2 class="text-3xl font-bold text-white mb-8">Orders</h2>
            @livewire('order-history')
        @endswitch
  
      </div>
    </div>
  </div>
</div>
@endsection
