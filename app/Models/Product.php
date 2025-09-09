<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'brand',
        'description',
        'price',
        'stock',
        'is_active',
        // Removed 'image' from fillable - now using product_images relationship
    ];

    protected function casts(): array
    {
        return [
            'price'     => 'decimal:2',
            'is_active' => 'bool',
        ];
    }

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(\App\Models\OrderItem::class);
    }

    /**
     * Fallback URL for legacy single-image column `image`.
     * Normalizes "public/..." or "storage/..." to "/storage/...".
     * Note: This is kept for backward compatibility but 'image' column is no longer used
     */
    public function getImageUrlAttribute(): ?string
    {
        // Check if we have a legacy image column value
        $raw = $this->attributes['image'] ?? null;
        if (!$raw) {
            return null;
        }

        if (Str::startsWith($raw, ['http://', 'https://'])) {
            return $raw;
        }

        $raw = ltrim($raw, '/');
        $raw = preg_replace('#^(public|storage)/#', '', $raw);

        return asset('storage/'.$raw);
    }

    /**
     * Convenience: unified image URL (primary relation first, then legacy).
     * Use in Blade: {{ $product->display_image_url }}
     */
    public function getDisplayImageUrlAttribute(): ?string
    {
        // If primaryImage is eager-loaded, this is O(1); otherwise will lazy-load once.
        return optional($this->primaryImage)->url ?? $this->image_url;
    }
}