<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Product;
use App\Models\ProductImage;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migrate existing product images to product_images table
        Product::whereNotNull('image')
            ->whereNotIn('id', function($query) {
                $query->select('product_id')
                      ->from('product_images')
                      ->whereNotNull('product_id');
            })
            ->chunk(100, function ($products) {
                foreach ($products as $product) {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'path' => $product->image,
                        // Add these fields if they exist in your product_images table:
                        // 'is_primary' => true,
                        // 'alt_text' => $product->name,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove migrated images (optional - be careful with this)
        // ProductImage::whereIn('product_id', function($query) {
        //     $query->select('id')->from('products')->whereNotNull('image');
        // })->delete();
    }
};