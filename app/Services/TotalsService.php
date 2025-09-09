<?php

namespace App\Services;

use Illuminate\Support\Collection;

class TotalsService
{
    /**
     * Calculate totals for a cart (supports unit_price/qty OR price/quantity).
     */
    public function cartTotals(Collection $items): array
    {
        $subtotal = (float) $items->sum(function ($i) {
            $price = $i->unit_price ?? $i->price ?? ($i->product->price ?? 0);
            $qty   = $i->qty ?? $i->quantity ?? 1;
            return (float)$price * (int)$qty;
        });

        $shippingFlat  = (float) config('shop.shipping_flat', 0);
        $freeThreshold = (float) config('shop.free_shipping_threshold', 0);
        $shipping      = $subtotal >= $freeThreshold ? 0.0 : $shippingFlat;

        return [
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'total'    => $subtotal + $shipping,
        ];
    }

    /**
     * Calculate totals for an order (supports unit_price/qty OR price/quantity).
     */
    public function orderTotals(Collection $items, ?float $existingShipping = null): array
    {
        $subtotal = (float) $items->sum(function ($i) {
            $price = $i->unit_price ?? $i->price ?? 0;
            $qty   = $i->qty ?? $i->quantity ?? 1;
            return (float)$price * (int)$qty;
        });

        if ($existingShipping !== null) {
            $shipping = (float) $existingShipping;
        } else {
            $shippingFlat  = (float) config('shop.shipping_flat', 0);
            $freeThreshold = (float) config('shop.free_shipping_threshold', 0);
            $shipping      = $subtotal >= $freeThreshold ? 0.0 : $shippingFlat;
        }

        return [
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'total'    => $subtotal + $shipping,
        ];
    }
}
