@extends('layouts.admin')
@section('title','Orders')
@section('page-title','Orders')
@section('content')
<div class="overflow-hidden rounded-2xl bg-white/5 backdrop-blur border border-white/10 shadow-lg">
    <table class="w-full text-left">
        <thead class="text-blue-300 border-b border-white/10 bg-black/20">
            <tr>
                <th class="px-4 py-3">#</th>
                <th class="px-4 py-3">Customer</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3">Subtotal</th>
                <th class="px-4 py-3">Total</th>
                <th class="px-4 py-3">Date</th>
                <th class="px-4 py-3">Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($orders as $o)
            <tr class="border-b border-white/5 hover:bg-blue-900/10">
                <td class="px-4 py-3 font-bold text-blue-200">{{ $o->id }}</td>
                <td class="px-4 py-3">{{ $o->user->name ?? '—' }}</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 text-xs rounded bg-blue-900/20 border border-blue-900/20 text-blue-300">{{ ucfirst($o->status) }}</span>
                </td>
                <td class="px-4 py-3">${{ number_format($o->subtotal,2) }}</td>
                <td class="px-4 py-3 font-semibold">${{ number_format($o->total,2) }}</td>
                <td class="px-4 py-3 text-gray-400">{{ $o->created_at->format('Y-m-d H:i') }}</td>
                <td class="px-4 py-3">
                    <a href="{{ route('admin.orders.show', $o) }}" class="text-blue-400 hover:underline mr-2">View</a>
                    <a href="{{ route('admin.orders.edit', $o) }}" class="text-blue-400 hover:underline mr-2">Edit</a>
                    <form action="{{ route('admin.orders.destroy', $o) }}" method="POST" class="inline">
                        @csrf @method('DELETE')
                        <button class="text-red-400 hover:underline" onclick="return confirm('Delete this order?')">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td class="px-4 py-6 text-center text-gray-400" colspan="7">No orders yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="mt-6">{{ $orders->links() }}</div>
@endsection
