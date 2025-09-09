<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Only create if missing
        if (!Schema::hasTable('cart_items')) {
            Schema::create('cart_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('cart_id')->constrained()->cascadeOnDelete();
                $table->foreignId('product_id')->constrained()->cascadeOnDelete();
                $table->unsignedInteger('qty');
                $table->decimal('unit_price', 10, 2);
                $table->timestamps();
                $table->unique(['cart_id','product_id']);
            });
        } else {
            // (Optional) ensure indexes/constraints exist if table was created manually
            Schema::table('cart_items', function (Blueprint $table) {
                // add missing columns/keys only if needed, comment out if already present
                if (!Schema::hasColumn('cart_items', 'qty')) {
                    $table->unsignedInteger('qty')->after('product_id');
                }
                if (!Schema::hasColumn('cart_items', 'unit_price')) {
                    $table->decimal('unit_price', 10, 2)->after('qty');
                }
                // You can’t re-add a unique if it already exists; add only if missing
                // $table->unique(['cart_id','product_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
