<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function pay(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'payment_method_id' => 'required|string',
            'billing_details' => 'array',
        ]);

        Stripe::setApiKey(config('services.stripe.secret'));

        $order = Order::findOrFail($request->order_id);

        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => $order->total * 100,
                'currency' => 'usd',
                'payment_method' => $request->payment_method_id,
                'confirmation_method' => 'manual',
                'confirm' => true,
                'receipt_email' => $request->billing_details['email'] ?? null,
                'description' => 'Order #' . $order->id,
                'metadata' => [
                    'order_id' => $order->id,
                    'user_id' => $order->user_id,
                ],
            ]);

            // Mark order as paid
            $order->status = 'paid';
            $order->payment_intent_id = $paymentIntent->id;
            $order->save();

            return response()->json(['success' => true, 'payment_intent' => $paymentIntent]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 400);
        }
    }

    public function show(Request $request)
    {
        $orderId = $request->query('order_id');
        $order = Order::where('id', $orderId)->where('user_id', Auth::id())->first();
        if (!$order) {
            return response()->view('payment.index', [
                'order' => null,
                'amount' => 0,
                'error' => 'Order not found or does not belong to you.'
            ], 200);
        }
        $amount = $order->total;
        return view('payment.index', compact('order', 'amount'));
    }
}