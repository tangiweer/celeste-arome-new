<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Céleste Arôme - @yield('title', 'Luxury Perfume')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-gradient-to-br from-black via-blue-900 to-black text-gray-100 font-sans">

    <!-- NAVBAR -->
    <nav class="backdrop-blur-md bg-white/10 fixed w-full z-30 shadow-lg border-b border-white/10">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <a href="{{ url('/') }}" class="text-2xl font-extrabold tracking-widest text-blue-200">
                Céleste <span class="text-blue-500">Arôme</span>
            </a>

            <div class="flex items-center space-x-6">
                {{-- Shop --}}
                @if (Route::has('shop.index'))
                    <a href="{{ route('shop.index') }}" class="hover:text-blue-400 transition">Shop</a>
                @else
                    <a href="{{ url('/shop') }}" class="hover:text-blue-400 transition">Shop</a>
                @endif

                {{-- Cart --}}
                @if (Route::has('cart.index'))
                    <a href="{{ route('cart.index') }}" class="hover:text-blue-400 transition">Cart</a>
                @else
                    <a href="{{ url('/cart') }}" class="hover:text-blue-400 transition">Cart</a>
                @endif

                {{-- My Account (no dropdown) --}}
                @auth
                    @php
                        $accountRoute = Route::has('account.index') ? route('account.index')
                                       : (Route::has('profile.edit') ? route('profile.edit')
                                       : url('/account'));
                    @endphp
                    <a href="{{ $accountRoute }}"
                       class="inline-flex items-center gap-2 px-3 py-2 rounded-md hover:text-white hover:bg-white/10 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5Zm-9 8a9 9 0 1 1 18 0z"/>
                        </svg>
                        <span>My Account</span>
                    </a>
                @endauth

                {{-- Auth (guest) --}}
                @guest
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}" class="hover:text-blue-400 transition">Login</a>
                    @else
                        <a href="{{ url('/login') }}" class="hover:text-blue-400 transition">Login</a>
                    @endif

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="hover:text-blue-400 transition">Register</a>
                    @endif
                @endguest
            </div>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main class="flex-1 pt-28 pb-12 px-4 xl:px-8">
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="border-t border-white/10 py-6 text-center text-sm text-gray-400">
        © {{ date('Y') }} <span class="font-semibold text-blue-300">Céleste Arôme</span>. All rights reserved.
    </footer>
    @livewireScripts
</body>

</html>
