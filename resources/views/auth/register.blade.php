@extends('layouts.app')
@section('title','Register')

@section('content')
<div class="flex items-center justify-center min-h-[70vh]">
  <div class="w-full max-w-md p-8 rounded-2xl backdrop-blur-md bg-white/10 border border-white/15 shadow-2xl">
    <h1 class="text-3xl font-bold text-center mb-6 text-blue-300">Create Account</h1>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
      @csrf

      <div>
        <label for="name" class="block text-sm mb-1 text-gray-300">Full Name</label>
        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
               class="w-full px-4 py-3 rounded-lg bg-white/5 border border-white/15 text-gray-100 placeholder-gray-400 focus:ring-2 focus:ring-blue-400">
        @error('name') <span class="text-sm text-rose-400">{{ $message }}</span> @enderror
      </div>

      <div>
        <label for="email" class="block text-sm mb-1 text-gray-300">Email Address</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required
               class="w-full px-4 py-3 rounded-lg bg-white/5 border border-white/15 text-gray-100 placeholder-gray-400 focus:ring-2 focus:ring-blue-400">
        @error('email') <span class="text-sm text-rose-400">{{ $message }}</span> @enderror
      </div>

      <div>
        <label for="password" class="block text-sm mb-1 text-gray-300">Password</label>
        <input id="password" type="password" name="password" required
               class="w-full px-4 py-3 rounded-lg bg-white/5 border border-white/15 text-gray-100 placeholder-gray-400 focus:ring-2 focus:ring-blue-400">
        @error('password') <span class="text-sm text-rose-400">{{ $message }}</span> @enderror
      </div>

      <div>
        <label for="password_confirmation" class="block text-sm mb-1 text-gray-300">Confirm Password</label>
        <input id="password_confirmation" type="password" name="password_confirmation" required
               class="w-full px-4 py-3 rounded-lg bg-white/5 border border-white/15 text-gray-100 placeholder-gray-400 focus:ring-2 focus:ring-blue-400">
      </div>

      <button type="submit"
              class="w-full py-3 rounded-lg bg-blue-600 hover:bg-blue-500 font-semibold transition">
        Register
      </button>
    </form>

    <p class="mt-6 text-center text-gray-400 text-sm">
      Already have an account?
      <a href="{{ route('login') }}" class="text-blue-300 hover:underline">Sign in</a>
    </p>
  </div>
</div>
@endsection
