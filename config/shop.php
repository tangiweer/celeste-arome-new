<?php

return [
    // Flat shipping fee applied if subtotal is below the free threshold
    'shipping_flat' => (float) env('SHOP_SHIPPING_FLAT', 0.0),

    // If subtotal >= threshold, shipping becomes 0
    'free_shipping_threshold' => (float) env('SHOP_FREE_SHIPPING_THRESHOLD', 0.0),
];
