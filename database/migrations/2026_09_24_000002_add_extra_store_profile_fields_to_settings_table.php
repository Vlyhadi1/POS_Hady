<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('store_email', 150)->nullable()->after('store_phone');
            $table->string('store_hours', 150)->nullable()->after('store_email');
            $table->string('store_social', 255)->nullable()->after('store_hours');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['store_email', 'store_hours', 'store_social']);
        });
    }
};