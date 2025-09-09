<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\TotalsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class PaymentController extends Controller
{
    public function __construct(private TotalsService $totals)
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {
        $orderId = $request->query('order_id');

        $query = Order::where('user_id', Auth::id())
            ->whereIn('status', ['draft', 'pending']) // show only unpaid, not-yet-placed orders
            ->with('items.product');

        if ($orderId) {
            $query->where('id', $orderId);
        } else {
            $query->latest();
        }

        $order = $query->first();

        if (!$order || $order->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Use 0 when 'shipping' column doesn't exist
        $shippingExists = Schema::hasColumn('orders', 'shipping');
        $shippingValue  = $shippingExists ? (float)($order->shipping ?? 0) : 0.0;

        // Ensure persisted totals are correct (recompute & sync if needed)
        $t = $this->totals->orderTotals($order->items, $shippingValue);
        $dirty = false;

        if ((float)$order->subtotal !== (float)$t['subtotal']) { $order->subtotal = $t['subtotal']; $dirty = true; }
        if ($shippingExists && (float)$order->shipping !== (float)$t['shipping']) { $order->shipping = $t['shipping']; $dirty = true; }
        if ((float)$order->total !== (float)$t['total']) { $order->total = $t['total']; $dirty = true; }

        if ($dirty) { $order->save(); }

        return view('payment.index', ['order' => $order]);
    }

    public function process(Request $request)
    {
        $data = $request->validate([
            'order_id'       => 'required|exists:orders,id',
            'payment_method' => 'required|in:card,cod',
        ]);

        /** @var Order $order */
        $order = Order::where('id', $data['order_id'])
            ->where('user_id', Auth::id())
            ->with('items')
            ->firstOrFail();

        // Guard against double-processing
        if (in_array($order->status, ['paid', 'cancelled'], true)) {
            return redirect()->route('payment.success')->with('success', 'This order was already processed.');
        }

        // Column guards
        $shippingExists     = Schema::hasColumn('orders', 'shipping');
        $paymentMethodExists = Schema::hasColumn('orders', 'payment_method');

        $shippingValue = $shippingExists ? (float)($order->shipping ?? 0) : 0.0;

        // Recalculate totals to ensure integrity
        $t = $this->totals->orderTotals($order->items, $shippingValue);
        $order->subtotal = $t['subtotal'];
        if ($shippingExists) { $order->shipping = $t['shipping'] ?? 0; }
        $order->total = $t['total'];

        // Persist chosen method if column exists
        if ($paymentMethodExists) {
            $order->payment_method = $data['payment_method'];
        }

        if ($data['payment_method'] === 'cod') {
            // COD: place order without marking as paid
            $order->status  = 'placed';
            $order->paid_at = null;
            $order->save();

            // Clear the cart AFTER order is placed
            Auth::user()->cart?->items()->delete();

            return redirect()->route('payment.success')->with('success', 'Order placed with Cash on Delivery.');
        }

        // Card flow (pseudo: assume success)
        $order->status  = 'paid';
        $order->paid_at = now();
        $order->save();

        // Clear the cart AFTER payment is successful
        Auth::user()->cart?->items()->delete();

        return redirect()->route('payment.success')->with('success', 'Payment completed.');
    }

    public function success()
    {
        return view('payment.success');
    }

    public function failed()
    {
        return view('payment.failed');
    }
}
