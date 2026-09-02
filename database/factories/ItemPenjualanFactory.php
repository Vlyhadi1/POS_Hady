<?php

namespace Database\Factories;

use App\Models\ItemPenjualan;
use App\Models\Produk;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemPenjualanFactory extends Factory
{
    protected $model = ItemPenjualan::class;

    public function definition(): array
    {
        $produk = Produk::inRandomOrder()->first();

        if (!$produk) {
            $produk = Produk::factory()->create();
        }

        $qty = $this->faker->numberBetween(1, min(10, max(1, $produk->stok)));

        return [
            'produk_id' => $produk->id,
            'kuantitas' => $qty,
            'harga_satuan' => $produk->harga_jual,
            'subtotal' => $produk->harga_jual * $qty,
        ];
    }
}
