<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OrderApiController extends Controller
{
    /**
     * Customer: List their own orders
     */
    public function index(Request $request)
    {
        $orders = $request->user()->orders()->with('items.product')->get();

        return response()->json(['orders' => $orders], 200);
    }

    /**
     * Customer: Show a single order
     */
    public function show(Request $request, $id)
    {
        $order = $request->user()->orders()->with('items.product')->find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        return response()->json($order, 200);
    }

    /**
     * Customer: Place a new order
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $total = 0;
            $orderItems = [];

            foreach ($validated['items'] as $item) {
                $product = Product::find($item['product_id']);
                $linePrice = $product->price * $item['quantity'];
                $total += $linePrice;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $linePrice, // total price for this order item
                ];
            }

            $order = $request->user()->orders()->create([
                'total' => $total,
                'status' => 'pending',
            ]);

            foreach ($orderItems as $item) {
                $order->items()->create($item);
            }

            DB::commit();

            return response()->json([
                'message' => 'Order placed successfully',
                'order' => $order->load('items.product')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to place order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Admin: List all orders
     */
    public function apiIndex()
{
    $orders = Order::with('user')->paginate(10);

    return response()->json($orders);
}



    /**
     * Admin: Show single order
     */
    public function apiShow($id)
    {
        $order = Order::with('items.product', 'user')->find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        return response()->json($order, 200);
    }

    /**
     * Admin: Delete order
     */
    public function apiDestroy($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $order->delete();

        return response()->json(['message' => 'Order deleted successfully'], 200);
    }
}
