@extends('layouts.admin')
@section('title','Customers')
@section('content')
<div class="flex items-center justify-between mb-8">
    <h1 class="text-2xl font-extrabold text-blue-300">Customers</h1>
</div>
<div class="overflow-hidden rounded-2xl bg-white/5 backdrop-blur border border-white/10 shadow-lg">
    <table class="w-full text-left">
        <thead class="text-blue-300 border-b border-white/10 bg-black/20">
            <tr>
                <th class="px-4 py-3">#</th>
                <th class="px-4 py-3">Name</th>
                <th class="px-4 py-3">Email</th>
                <th class="px-4 py-3">Orders</th>
                <th class="px-4 py-3">Joined</th>
                <th class="px-4 py-3">Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($customers as $customer)
            <tr class="border-b border-white/5 hover:bg-blue-900/10">
                <td class="px-4 py-3 font-bold text-blue-200">{{ $customer->id }}</td>
                <td class="px-4 py-3">{{ $customer->name }}</td>
                <td class="px-4 py-3">{{ $customer->email }}</td>
                <td class="px-4 py-3">{{ $customer->orders_count ?? 0 }}</td>
                <td class="px-4 py-3 text-gray-400">{{ $customer->created_at->format('Y-m-d') }}</td>
                <td class="px-4 py-3">
                    <a href="{{ route('admin.customers.show', $customer) }}" class="text-blue-400 hover:underline mr-2">View</a>
                    <a href="{{ route('admin.customers.edit', $customer) }}" class="text-blue-400 hover:underline mr-2">Edit</a>
                    <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" class="inline">
                        @csrf @method('DELETE')
                        <button class="text-red-400 hover:underline" onclick="return confirm('Delete this customer?')">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td class="px-4 py-6 text-center text-gray-400" colspan="6">No customers found.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="mt-6">{{ $customers->links() }}</div>
@endsection
