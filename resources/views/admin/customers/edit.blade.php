@extends('layouts.admin')

@section('title', 'Edit Customer')

@section('content')
<div class="flex justify-center">
    <div class="w-full max-w-2xl">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-2xl font-extrabold text-blue-300">Edit Customer</h1>
            <a href="{{ route('admin.customers.show', $customer) }}" class="bg-white/10 hover:bg-white/20 text-blue-300 px-4 py-2 rounded-lg transition-colors border border-white/10">
                Back to Customer
            </a>
        </div>

        <div class="bg-white/10 rounded-xl p-6 shadow border border-white/10">
            <form action="{{ route('admin.customers.update', $customer) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-blue-300 mb-2">Full Name</label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="{{ old('name', $customer->name) }}"
                               class="w-full rounded-lg bg-black/10 border border-white/10 px-4 py-3 text-blue-200 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Enter customer's full name"
                               required>
                        @error('name')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-blue-300 mb-2">Email Address</label>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               value="{{ old('email', $customer->email) }}"
                               class="w-full rounded-lg bg-black/10 border border-white/10 px-4 py-3 text-blue-200 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Enter customer's email address"
                               required>
                        @error('email')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Customer Info Display -->
                    <div class="bg-white/5 rounded-lg p-4 border border-white/5">
                        <h3 class="text-sm font-medium text-blue-300 mb-3">Customer Information</h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-400">Customer ID:</span>
                                <span class="text-blue-200 font-semibold ml-2">#{{ $customer->id }}</span>
                            </div>
                            <div>
                                <span class="text-gray-400">Member Since:</span>
                                <span class="text-blue-200 font-semibold ml-2">{{ $customer->created_at->format('M d, Y') }}</span>
                            </div>
                            <div>
                                <span class="text-gray-400">Total Orders:</span>
                                <span class="text-blue-200 font-semibold ml-2">{{ $customer->orders->count() ?? 0 }}</span>
                            </div>
                            <div>
                                <span class="text-gray-400">Last Updated:</span>
                                <span class="text-blue-200 font-semibold ml-2">{{ $customer->updated_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-4 pt-6 border-t border-white/10">
                        <button type="submit" 
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Update Customer
                        </button>
                        <a href="{{ route('admin.customers.show', $customer) }}" 
                           class="flex-1 text-center bg-white/10 hover:bg-white/20 text-blue-300 px-6 py-3 rounded-lg font-semibold transition-colors border border-white/10">
                            Cancel
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="fixed top-4 right-4 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg z-50">
        {{ session('success') }}
    </div>
@endif

@endsection