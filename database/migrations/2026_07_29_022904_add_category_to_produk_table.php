<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('produk', function (Blueprint $table) {

            $table->foreignId('category_id')
                ->nullable()
                ->constrained('categories')
                ->nullOnDelete();

            $table->string('satuan')->default('Pcs');

            $table->integer('minimum_stok')->default(0);

            $table->text('deskripsi')->nullable();

            $table->boolean('status')->default(true);
        });
    }

    public function down(): void
    {
        Schema::table('produk', function (Blueprint $table) {

            $table->dropForeign(['category_id']);
            $table->dropColumn([
                'category_id',
                'satuan',
                'minimum_stok',
                'deskripsi',
                'status',
            ]);
        });
    }
};