<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_landing_pages', function (Blueprint $table) {
            // Store per-variant prices: {"m": {"price": 999, "old_price": 1500}, "xl": {"price": 1199, "old_price": 1800}}
            $table->json('variant_prices')->nullable()->after('new_price');
        });
    }

    public function down(): void
    {
        Schema::table('product_landing_pages', function (Blueprint $table) {
            $table->dropColumn('variant_prices');
        });
    }
};
