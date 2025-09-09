<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index() {
        // Show orders page
        return view('orders.index');
    }

    public function apiIndex() {
        return response()->json([ 'orders' => \App\Models\Order::all() ]);
    }
    public function apiShow($order) {
        $order = \App\Models\Order::findOrFail($order);
        return response()->json($order);
    }
    public function apiUpdate(Request $request, $order) {
        $order = \App\Models\Order::findOrFail($order);
        $order->update($request->all());
        return response()->json($order);
    }
    public function apiDestroy($order) {
        $order = \App\Models\Order::findOrFail($order);
        $order->delete();
        return response()->json(['success' => true]);
    }
}
