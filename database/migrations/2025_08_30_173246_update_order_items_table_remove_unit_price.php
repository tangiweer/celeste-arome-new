<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Remove unit_price column
            if (Schema::hasColumn('order_items', 'unit_price')) {
                $table->dropColumn('unit_price');
            }

            // Ensure price column is non-nullable and has default
            $table->decimal('price', 8, 2)->default(0)->change();
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Re-add unit_price if rolling back
            $table->decimal('unit_price', 8, 2)->nullable();
        });
    }
};
