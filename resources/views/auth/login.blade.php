@extends('layouts.app')
@section('title','Login')

@section('content')
<div class="flex items-center justify-center min-h-[70vh]">
  <div class="w-full max-w-md p-8 rounded-2xl backdrop-blur-md bg-white/10 border border-white/15 shadow-2xl">
    <h1 class="text-3xl font-bold text-center mb-6 text-blue-300">Welcome Back</h1>

    @if(session('status'))
      <div class="mb-4 text-sm text-emerald-400">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
      @csrf

      <div>
        <label for="email" class="block text-sm mb-1 text-gray-300">Email Address</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
               class="w-full px-4 py-3 rounded-lg bg-white/5 border border-white/15 text-gray-100 placeholder-gray-400 focus:ring-2 focus:ring-blue-400">
        @error('email') <span class="text-sm text-rose-400">{{ $message }}</span> @enderror
      </div>

      <div>
        <label for="password" class="block text-sm mb-1 text-gray-300">Password</label>
        <input id="password" type="password" name="password" required
               class="w-full px-4 py-3 rounded-lg bg-white/5 border border-white/15 text-gray-100 placeholder-gray-400 focus:ring-2 focus:ring-blue-400">
        @error('password') <span class="text-sm text-rose-400">{{ $message }}</span> @enderror
      </div>

      <div class="flex items-center justify-between text-sm text-gray-400">
        <label class="inline-flex items-center gap-2">
          <input type="checkbox" name="remember" class="rounded bg-transparent border-white/20">
          Remember me
        </label>
        <a href="{{ route('password.request') }}" class="hover:text-blue-300">Forgot Password?</a>
      </div>

      <button type="submit"
              class="w-full py-3 rounded-lg bg-blue-600 hover:bg-blue-500 font-semibold transition">
        Sign In
      </button>
    </form>

    <p class="mt-6 text-center text-gray-400 text-sm">
      New here?
      <a href="{{ route('register') }}" class="text-blue-300 hover:underline">Create an account</a>
    </p>
  </div>
</div>
@endsection
