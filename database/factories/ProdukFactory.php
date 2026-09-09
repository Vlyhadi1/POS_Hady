<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProdukFactory extends Factory
{
    protected $model = Produk::class;

    public function definition(): array
    {
        $hargaBeli = $this->faker->numberBetween(10000, 500000);

        return [
            'user_id' => User::whereHas('role', fn($q) => $q->where('name', 'admin'))->inRandomOrder()->value('id')
                ?? User::inRandomOrder()->value('id'),
            'category_id' => Category::inRandomOrder()->value('id'),
            'foto' => null,
            'nama' => ucwords($this->faker->words(3, true)),
            'harga_beli' => $hargaBeli,
            'harga_jual' => $hargaBeli + $this->faker->numberBetween(5000, 100000),
            'stok' => $this->faker->numberBetween(0, 100),
            'satuan' => $this->faker->randomElement(['Pcs', 'Box', 'Botol', 'Pack']),
            'minimum_stok' => $this->faker->numberBetween(3, 10),
            'deskripsi' => $this->faker->optional()->sentence(),
            'status' => true,
        ];
    }
}
