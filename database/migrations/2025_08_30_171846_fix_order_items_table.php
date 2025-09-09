<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Drop the old qty column
            if (Schema::hasColumn('order_items', 'qty')) {
                $table->dropColumn('qty');
            }

            // Ensure quantity column exists and is NOT NULL with default 1
            if (!Schema::hasColumn('order_items', 'quantity')) {
                $table->integer('quantity')->default(1);
            } else {
                $table->integer('quantity')->default(1)->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Revert changes if needed
            if (!Schema::hasColumn('order_items', 'qty')) {
                $table->integer('qty')->default(1);
            }
        });
    }
};
