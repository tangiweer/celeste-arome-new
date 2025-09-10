<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * GET /admin/orders
     * Provides $orders to resources/views/admin/orders/index.blade.php
     */
    public function index(Request $request)
    {
        $q       = trim((string) $request->query('q', ''));
        $status  = trim((string) $request->query('status', ''));
        $perPage = (int) $request->query('per_page', 20);

        $orders = Order::query()
            ->when($status !== '', fn($qq) => $qq->where('status', $status))
            ->when($q !== '', function ($qq) use ($q) {
                $qq->where(function ($sub) use ($q) {
                    $sub->where('id', $q)
                        ->orWhere('reference', 'like', "%{$q}%")
                        ->orWhereHas('user', function ($u) use ($q) {
                            $u->where('name', 'like', "%{$q}%")
                              ->orWhere('email', 'like', "%{$q}%");
                        });
                });
            })
            ->with('user')          // assumes Order::user() exists
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.orders.index', compact('orders', 'q', 'status'));
    }

    /**
     * GET /admin/orders/{order}
     */
    public function show(Order $order)
    {
        // If you have items relation, eager-load it (adjust relation names if different)
        $order->load(['user', 'items.product']);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * PUT /admin/orders/{order}
     * Update status/notes (adjust fields to your schema).
     */
    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => 'required|string|in:pending,processing,shipped,delivered,cancelled,refunded',
            'notes'  => 'nullable|string',
        ]);

        $order->update($data);

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Order updated successfully.');
    }

    // The rest of resource methods can be added later if you need them:
    public function create() {}
    public function store(Request $request) {}
    public function edit(Order $order) { return view('admin.orders.edit', compact('order')); }
    public function destroy(Order $order) {
        $order->update(['status' => 'cancelled']); // or $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Order cancelled.');
    }
}
