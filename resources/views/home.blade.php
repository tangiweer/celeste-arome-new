@extends('layouts.app')

@section('content')
<div class="bg-gradient-to-b from-black via-blue-900 to-black min-h-screen">
	<div class="max-w-7xl mx-auto px-4 py-16">
		<!-- Hero Section -->
		<div class="flex flex-col md:flex-row items-center gap-10 mb-20">
			<div class="flex-1">
				<h1 class="text-5xl md:text-6xl font-bold text-blue-200 mb-6 leading-tight">Discover Your Signature Scent</h1>
				<p class="text-lg text-gray-300 mb-8">Curated fragrances from the world's top brands. Find your next favorite perfume with us.</p>
				<a href="{{ route('shop.index') }}" class="inline-block px-8 py-4 rounded-lg bg-blue-600 text-white font-bold text-lg shadow-lg hover:bg-blue-500 transition">Shop Now</a>
			</div>
			<div class="flex-1 flex justify-center">
				<img src="storage/hero-perfume.jpeg" alt="Perfume Bottles" class="w-full max-w-md rounded-2xl shadow-xl border border-white/10">
			</div>
		</div>
		<!-- Featured Brands -->
		<div class="mb-20">
			<h2 class="text-2xl font-bold text-blue-200 mb-6">Featured Brands</h2>
			<div class="flex flex-wrap gap-6">
				@foreach($brands as $brand)
					<div class="bg-black/30 border border-white/10 rounded-xl px-6 py-4 text-blue-300 font-semibold shadow">
						{{ $brand }}
					</div>
				@endforeach
			</div>
		</div>
		<!-- Popular Products -->
		<div class="mb-20">
			<h2 class="text-2xl font-bold text-blue-200 mb-6">Popular Products</h2>
			<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
				@foreach($popularProducts as $product)
					<div class="bg-white/10 rounded-2xl shadow-xl border border-white/10 p-6 flex flex-col">
						<a href="{{ route('shop.show', $product->slug) }}">
							<img src="{{ $product->primaryImage ? asset('storage/'.$product->primaryImage->path) : ($product->image ? asset('storage/'.$product->image) : 'https://via.placeholder.com/400x400') }}" alt="{{ $product->name }}" class="w-full h-56 object-cover rounded-xl mb-4">
						</a>
						<h3 class="text-lg font-bold text-blue-200 mb-2">
							<a href="{{ route('shop.show', $product->slug) }}">{{ $product->name }}</a>
						</h3>
						<div class="text-blue-400 font-semibold mb-2">{{ $product->brand }}</div>
						<p class="text-gray-300 mb-4">{{ $product->description }}</p>
						<div class="mt-auto flex justify-between items-center gap-2">
							<span class="text-blue-300 font-bold text-xl">${{ number_format($product->price, 2) }}</span>
							<form method="POST" action="{{ route('cart.add', $product) }}">
								@csrf
								<button class="px-4 py-2 bg-blue-600 rounded-lg hover:bg-blue-500">Add</button>
							</form>
							@livewire('shop.heart-toggle', ['product' => $product], key('home-'.$product->id))
						</div>
					</div>
				@endforeach
			</div>
		</div>
		<!-- Footer Newsletter & Info -->
		<footer class="bg-black/90 border-t border-white/10 mt-24 pt-12 pb-4 text-gray-300">
			<div class="max-w-7xl mx-auto px-4">
				<div class="text-center mb-10">
					<h2 class="text-2xl font-bold text-blue-300 mb-2">Stay in the Scent</h2>
					<p class="mb-4 text-blue-400">Be the first to discover our new arrivals, exclusive collections, and insider fragrance tips.</p>
					<div class="flex flex-col md:flex-row justify-center items-center gap-4 max-w-xl mx-auto">
						<input type="email" name="email" required placeholder="Your email address" class="rounded bg-blue-950 border border-blue-800 px-4 py-2 text-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-500 w-full md:w-64 bg-opacity-80">
						<button type="button" class="px-6 py-2 rounded bg-blue-600 text-white font-bold hover:bg-blue-500 transition">Subscribe</button>
					</div>
				</div>
				<div class="grid grid-cols-1 md:grid-cols-4 gap-10 py-10 border-t border-white/10">
					<div>
							<h3 class="text-xl font-bold text-blue-300 mb-2">Celeste Arome</h3>
							<p class="text-blue-400 text-sm mb-4">The world's finest luxury fragrances, crafted with passion and perfected through generations of expertise.</p>
						<div class="flex gap-3 text-lg">
							<a href="#" class="hover:text-blue-400"><i class="fab fa-instagram"></i></a>
							<a href="#" class="hover:text-blue-400"><i class="fab fa-facebook"></i></a>
							<a href="#" class="hover:text-blue-400"><i class="fab fa-twitter"></i></a>
							<a href="#" class="hover:text-blue-400"><i class="fab fa-youtube"></i></a>
						</div>
					</div>
					<div>
							<h4 class="font-bold text-blue-300 mb-2">Shop</h4>
							<ul class="space-y-1 text-sm">
								<li><a href="#" class="hover:text-blue-400">Women's Fragrances</a></li>
								<li><a href="#" class="hover:text-blue-400">Men's Colognes</a></li>
								<li><a href="#" class="hover:text-blue-400">Unisex Perfumes</a></li>
								<li><a href="#" class="hover:text-blue-400">Gift Sets</a></li>
								<li><a href="#" class="hover:text-blue-400">Limited Editions</a></li>
							</ul>
					</div>
					<div>
							<h4 class="font-bold text-blue-300 mb-2">Customer Care</h4>
							<ul class="space-y-1 text-sm">
								<li><a href="#" class="hover:text-blue-400">Contact Us</a></li>
								<li><a href="#" class="hover:text-blue-400">Shipping & Returns</a></li>
								<li><a href="#" class="hover:text-blue-400">Size Guide</a></li>
								<li><a href="#" class="hover:text-blue-400">FAQ</a></li>
								<li><a href="#" class="hover:text-blue-400">Track Your Order</a></li>
							</ul>
					</div>
					<div>
							<h4 class="font-bold text-blue-300 mb-2">Company</h4>
							<ul class="space-y-1 text-sm">
								<li><a href="#" class="hover:text-blue-400">About Celeste</a></li>
								<li><a href="#" class="hover:text-blue-400">Careers</a></li>
								<li><a href="#" class="hover:text-blue-400">Press</a></li>
								<li><a href="#" class="hover:text-blue-400">Sustainability</a></li>
								<li><a href="#" class="hover:text-blue-400">Privacy Policy</a></li>
							</ul>
					</div>
				</div>
				<div class="text-center text-xs text-gray-500 border-t border-white/10 pt-4 mt-4">
					&copy; 2025 Celeste Arome. All rights reserved.
					<span class="mx-2">|</span>
					<a href="#" class="hover:text-blue-200">Terms of Service</a>
					<span class="mx-2">|</span>
					<a href="#" class="hover:text-blue-200">Privacy Policy</a>
					<span class="mx-2">|</span>
					<a href="#" class="hover:text-blue-200">Cookie Policy</a>
				</div>
			</div>
		</footer>
	</div>
</div>
@endsection