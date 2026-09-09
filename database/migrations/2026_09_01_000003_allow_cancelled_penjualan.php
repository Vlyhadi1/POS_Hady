<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE penjualan MODIFY status ENUM('OPEN','COMPLETED','CANCELLED') NOT NULL");
        }
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("UPDATE penjualan SET status = 'OPEN' WHERE status = 'CANCELLED'");
            DB::statement("ALTER TABLE penjualan MODIFY status ENUM('OPEN','COMPLETED') NOT NULL");
        }
    }
};
