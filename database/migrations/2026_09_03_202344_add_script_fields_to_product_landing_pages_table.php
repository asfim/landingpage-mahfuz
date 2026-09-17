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
            $table->text('header_script')->nullable();
            $table->text('body_script')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_landing_pages', function (Blueprint $table) {
            $table->dropColumn(['header_script', 'body_script']);
        });
    }
};
