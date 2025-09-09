<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Céleste Arôme — Luxury Perfume</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-gradient-to-br from-black via-blue-900 to-black text-gray-100">

    {{-- Sticky translucent header --}}
    <header class="fixed inset-x-0 top-0 z-40 border-b border-white/10 bg-white/10 backdrop-blur">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
            <a href="{{ route('home') }}" class="text-2xl font-extrabold tracking-widest text-blue-200">
                Céleste <span class="text-blue-500">Arôme</span>
            </a>
            <nav class="hidden md:flex items-center gap-6">
                <a href="{{ route('shop.index') }}" class="hover:text-blue-300">Shop</a>
                <a href="{{ route('cart.index') }}" class="hover:text-blue-300">Cart</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="hover:text-blue-300">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">@csrf
                        <button class="hover:text-red-400">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="hover:text-blue-300">Login</a>
                    <a href="{{ route('register') }}" class="hover:text-blue-300">Register</a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="pt-20 md:pt-24">
        {{-- HERO --}}
        <section class="relative">
            <div
                class="absolute inset-0 bg-[radial-gradient(900px_500px_at_85%_-10%,rgba(59,130,246,0.25),transparent)] pointer-events-none">
            </div>
            <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-2 gap-10 items-center py-14">
                <div>
                    <h1 class="text-5xl md:text-6xl font-extrabold leading-tight">
                        Find Your <span class="text-blue-400">Signature</span> Scent
                    </h1>
                    <p class="mt-5 text-lg text-gray-300">
                        A curated edit of iconic houses — Chanel, Dior, YSL, Tom Ford — and our own Céleste exclusives.
                    </p>
                    <div class="mt-8 flex gap-4">
                        <a href="{{ route('shop.index') }}"
                            class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-500">Shop Now</a>
                        <a href="#collections"
                            class="px-6 py-3 rounded-xl bg-white/10 border border-white/15 hover:bg-white/20">Collections</a>
                    </div>
                </div>
                <div class="relative">
                    <div class="rounded-3xl overflow-hidden border border-white/10 shadow-2xl">

                        <img src="{{ asset('storage/products/perfume.jpeg') }}" class="w-full h-[440px] object-cover"
                            alt="Perfume">
                    </div>
                    <div class="absolute -bottom-6 -left-6 w-40 h-40 blur-3xl bg-blue-500/30 rounded-full"></div>
                </div>
            </div>
        </section>
        {{-- COLLECTIONS --}}
        <section id="collections" class="max-w-7xl mx-auto px-6 mt-6">
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach ([['name' => 'New In', 'img' => 'https://images.unsplash.com/photo-1545239351-1141bd82e8a6?q=80&w=900&auto=format&fit=crop'], ['name' => 'For Her', 'img' => 'https://images.unsplash.com/photo-1511285560929-80b456fea0bc?q=80&w=900&auto=format&fit=crop'], ['name' => 'For Him', 'img' => 'https://images.unsplash.com/photo-1519751138087-5a3a3a6e52d0?q=80&w=900&auto=format&fit=crop'], ['name' => 'Oud & Woods', 'img' => 'https://images.unsplash.com/photo-1541643600914-78b084683601?q=80&w=900&auto=format&fit=crop']] as $c)
                    <a href="{{ route('shop.index') }}"
                        class="group relative rounded-2xl overflow-hidden border border-white/10 bg-white/5">
                        <img src="{{ $c['img'] }}"
                            class="h-48 w-full object-cover group-hover:scale-105 transition" alt="">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        <div class="absolute bottom-3 left-4 text-lg font-semibold">{{ $c['name'] }}</div>
                    </a>
                @endforeach
            </div>
        </section>

        {{-- BEST SELLERS (3 latest active) --}}
        <section class="max-w-7xl mx-auto px-6 mt-12">
            <div class="flex items-end justify-between mb-4">
                <h2 class="text-2xl font-semibold text-blue-300">Bestsellers</h2>
                <a class="text-sm text-blue-300 hover:text-blue-200" href="{{ route('shop.index') }}">View all →</a>
            </div>
            <div class="grid md:grid-cols-3 gap-6">
                @php
                    $list = \App\Models\Product::with('primaryImage')->where('is_active', 1)->latest()->take(3)->get();
                @endphp
                @foreach ($list as $p)
                    @php
                        $img = $p->primaryImage->url ?? ($p->image_url ?? 'https://via.placeholder.com/600x400');
                    @endphp
                    <a href="{{ route('shop.show', $p->slug) }}"
                        class="block rounded-xl overflow-hidden bg-white/10 border border-white/10 hover:bg-white/15 transition">
                        <img src="{{ $img }}" class="w-full h-56 object-cover" alt="{{ $p->name }}">
                        <div class="p-4">
                            <div class="text-sm text-gray-400">{{ $p->brand }}</div>
                            <div class="font-semibold">{{ $p->name }}</div>
                            <div class="text-blue-300 font-bold">${{ number_format($p->price, 2) }}</div>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>

        {{-- Featured --}}
        <section id="featured" class="max-w-7xl mx-auto px-6 mt-24">
            <h2 class="text-3xl font-bold text-blue-300 mb-8">Featured Selections</h2>
            <div class="grid md:grid-cols-3 gap-8">
                @foreach (\App\Models\Product::with('primaryImage')->where('is_active', 1)->latest()->take(3)->get() as $p)
                    @php
                        $img = $p->primaryImage->url ?? ($p->image_url ?? 'https://via.placeholder.com/600x400');
                    @endphp
                    <div
                        class="group rounded-xl overflow-hidden bg-white/10 border border-white/10 hover:bg-white/20 transition transform hover:-translate-y-1 duration-300 shadow-md hover:shadow-lg">
                        <a href="{{ route('shop.show', $p->slug) }}">
                            <img src="{{ $img }}"
                                class="w-full h-56 object-cover group-hover:scale-105 transition-transform duration-500 ease-out"
                                alt="{{ $p->name }}">
                        </a>
                        <div class="p-4 space-y-2">
                            <div class="text-sm text-gray-400">{{ $p->brand }}</div>
                            <div
                                class="font-semibold group-hover:text-blue-300 transition-colors duration-300 truncate">
                                {{ $p->name }}
                            </div>
                            <div class="text-blue-300 font-bold">${{ number_format($p->price, 2) }}</div>
                            <div class="mt-4 flex gap-3">
                                <form action="{{ route('cart.store', $p->id) }}" method="POST" class="flex-1">@csrf
                                    <button type="submit"
                                        class="w-full px-3 py-2 rounded-lg bg-blue-600 hover:bg-blue-500 text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        Add to Cart
                                    </button>
                                </form>
                                <form action="{{ route('wishlist.store', $p->id) }}" method="POST">@csrf
                                    <button type="submit"
                                        class="px-3 py-2 rounded-lg bg-white/10 border border-white/15 hover:bg-white/20 text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        ❤
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </main>

    <footer class="mt-16 border-t border-white/10 py-8 text-center text-sm text-gray-400">
        © {{ date('Y') }} <span class="font-semibold text-blue-300">Céleste Arôme</span>. All rights reserved.
    </footer>

    @livewireScripts
</body>

</html>
