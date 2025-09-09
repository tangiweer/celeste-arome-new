<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\TotalsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function __construct(private TotalsService $totals)
    {
        $this->middleware('auth');
    }

    /** Show checkout page */
    public function create()
    {
        $cart = Auth::user()
            ->cart()
            ->with(['items.product'])
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $t = $this->totals->cartTotals($cart->items);

        return view('checkout.index', [
            'cart'     => $cart,
            'items'    => $cart->items,
            'subtotal' => $t['subtotal'],
            'shipping' => $t['shipping'],
            'total'    => $t['total'],
        ]);
    }

    /**
     * Place order: lock stock, create order, (DO NOT clear cart yet), redirect to payment.
     * Cart items are preserved so "Back to Checkout" still shows the same basket.
     * Clear the cart only after successful payment (in PaymentController@process).
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $cart = $user->cart()->with(['items.product'])->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        try {
            /** @var \App\Models\Order $order */
            $order = DB::transaction(function () use ($user, $cart) {

                $productIds = $cart->items->pluck('product_id')->all();

                // Lock rows for stock accuracy
                $locked = DB::table('products')
                    ->whereIn('id', $productIds)
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                foreach ($cart->items as $i) {
                    $p        = $locked[$i->product_id] ?? null;
                    $isActive = (int)($p->is_active ?? 0);
                    $stock    = (int)($p->stock ?? 0);
                    $qty      = (int)($i->qty ?? $i->quantity ?? 1);

                    if (!$p || !$isActive || $qty > $stock) {
                        throw new \RuntimeException("Insufficient stock for {$i->product->name}.");
                    }
                }

                // Compute totals with service (no hard-coding)
                $t = $this->totals->cartTotals($cart->items);

                $order = Order::create([
                    'user_id'  => $user->id,
                    'status'   => 'pending',
                    'subtotal' => $t['subtotal'],
                    'shipping' => $t['shipping'],
                    'total'    => $t['total'],
                ]);

                foreach ($cart->items as $i) {
                    $qty   = (int)($i->qty ?? $i->quantity ?? 1);
                    $price = (float)($i->unit_price ?? $i->price ?? ($i->product->price ?? 0));

                    // Use your order_items columns: quantity, price
                    $order->items()->create([
                        'product_id' => $i->product_id,
                        'quantity'   => $qty,
                        'price'      => $price,
                    ]);

                    // Still decrement stock here so the items are reserved for this order.
                    DB::table('products')
                        ->where('id', $i->product_id)
                        ->decrement('stock', $qty);
                }

                // IMPORTANT: Do NOT clear the cart here — keep items until payment success.
                // $cart->items()->delete();  <-- removed on purpose

                return $order;
            });
        } catch (\RuntimeException $ex) {
            return back()->with('error', $ex->getMessage());
        } catch (\Throwable $ex) {
            return back()->with('error', 'Checkout failed. Please try again.');
        }

        if ($order && $order->id) {
            // Redirect to payment page with order_id in query string
            return redirect()->route('payment.index', ['order_id' => $order->id]);
        }

        return back()->with('error', 'Order could not be created.');
    }
}
