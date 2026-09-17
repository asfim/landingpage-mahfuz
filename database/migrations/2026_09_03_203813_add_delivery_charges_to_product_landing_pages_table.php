<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('product_landing_pages', function (Blueprint $table) {
            $table->decimal('inside_dhaka_charge', 10, 2)->default(60)->after('new_price');
            $table->decimal('outside_dhaka_charge', 10, 2)->default(120)->after('inside_dhaka_charge');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_landing_pages', function (Blueprint $table) {
            $table->dropColumn(['inside_dhaka_charge', 'outside_dhaka_charge']);
        });
    }
};
