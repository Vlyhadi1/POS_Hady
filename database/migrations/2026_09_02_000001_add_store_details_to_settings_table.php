<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('store_address', 255)->nullable()->after('store_name');
            $table->string('store_phone', 30)->nullable()->after('store_address');
            $table->string('store_logo')->nullable()->after('store_phone');
            $table->string('receipt_footer', 255)->nullable()->after('low_stock_limit');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['store_address', 'store_phone', 'store_logo', 'receipt_footer']);
        });
    }
};
