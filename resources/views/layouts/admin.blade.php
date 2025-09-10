<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title','Admin • Céleste Arôme')</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="min-h-screen text-white bg-gradient-to-b from-[#0b1020] via-[#0e1b3a] to-black">
  <div class="min-h-screen flex">
    <aside class="w-64 hidden md:flex flex-col border-r border-white/10 bg-black/30 backdrop-blur">
      <div class="h-14 flex items-center px-5 font-extrabold text-xl tracking-tight">
        <a href="{{ route('admin.dashboard') }}"><span class="text-white">Céleste</span> <span class="text-blue-400">Admin</span></a>
      </div>

      <nav class="p-4 space-y-2 text-sm font-semibold">
        <a class="block px-3 py-2 rounded hover:bg-blue-900/20 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-900/20' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard</a>

        <a class="block px-3 py-2 rounded hover:bg-blue-900/20 {{ request()->routeIs('admin.categories.*') ? 'bg-blue-900/20' : '' }}" href="{{ route('admin.categories.index') }}">Categories</a>

        <a class="block px-3 py-2 rounded hover:bg-blue-900/20 {{ request()->routeIs('admin.products.*') ? 'bg-blue-900/20' : '' }}" href="{{ route('admin.products.index') }}">Products</a>

        {{-- Guard Orders link to avoid RouteNotFoundException --}}
        @if (\Illuminate\Support\Facades\Route::has('admin.orders.index'))
          <a class="block px-3 py-2 rounded hover:bg-blue-900/20 {{ request()->routeIs('admin.orders.*') ? 'bg-blue-900/20' : '' }}" href="{{ route('admin.orders.index') }}">Orders</a>
        @endif

        <a class="block px-3 py-2 rounded hover:bg-blue-900/20 {{ request()->routeIs('admin.customers.*') ? 'bg-blue-900/20' : '' }}" href="{{ route('admin.customers.index') }}">Customers</a>

        <a class="block px-3 py-2 rounded hover:bg-blue-900/20 {{ request()->routeIs('admin.settings.*') ? 'bg-blue-900/20' : '' }}" href="{{ route('admin.settings.index') }}">Settings</a>

        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button class="block w-full text-left px-3 py-2 rounded hover:bg-blue-900/20">Logout</button>
        </form>
      </nav>
    </aside>

    <div class="flex-1 flex flex-col">
      <header class="h-14 border-b border-white/10 bg-black/30 backdrop-blur flex items-center justify-between px-4">
        {{-- Guard "Back to shop" in case shop route name differs --}}
        @php
          $shopHref = \Illuminate\Support\Facades\Route::has('shop.index') ? route('shop.index') : url('/shop');
        @endphp
        <a href="{{ $shopHref }}" class="text-sm hover:text-blue-300 font-semibold">← Back to shop</a>

        <div class="flex items-center gap-4 text-sm font-semibold">
          <span>{{ auth()->user()->name ?? 'Admin' }}</span>
          <form method="POST" action="{{ route('logout') }}">@csrf
            <button class="hover:text-blue-300">Logout</button>
          </form>
        </div>
      </header>

      <main class="flex-1 max-w-6xl mx-auto w-full px-4 py-8">
        @if(session('success'))
          <div class="mb-4 rounded bg-emerald-600/15 border border-emerald-500/30 text-emerald-200 px-4 py-3">
            {{ session('success') }}
          </div>
        @endif

        @if($errors->any())
          <div class="mb-4 rounded bg-rose-600/15 border border-rose-500/30 text-rose-200 px-4 py-3">
            <ul class="list-disc ml-6">
              @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        @yield('content')
      </main>
    </div>
  </div>
</body>
</html>
