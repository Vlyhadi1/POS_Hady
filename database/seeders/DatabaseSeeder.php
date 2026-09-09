<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
        ]);

        $admin = User::whereHas('role', fn ($q) => $q->where('name', 'admin'))->first();

        $categories = [
            ['nama'=>'Elektronik','icon'=>'bi-cpu','warna'=>'#3b82f6','deskripsi'=>'Produk elektronik','status'=>true],
            ['nama'=>'Makanan','icon'=>'bi-egg-fried','warna'=>'#f59e0b','deskripsi'=>'Produk makanan','status'=>true],
            ['nama'=>'Minuman','icon'=>'bi-cup-straw','warna'=>'#06b6d4','deskripsi'=>'Produk minuman','status'=>true],
            ['nama'=>'Lainnya','icon'=>'bi-box-seam','warna'=>'#22c55e','deskripsi'=>'Produk lainnya','status'=>true],
        ];
        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['nama' => $category['nama']],
                array_merge($category, ['user_id' => $admin?->id])
            );
        }

        Setting::updateOrCreate(['id' => 1], [
            'store_name' => 'POS HADI',
            'currency' => 'IDR',
            'low_stock_limit' => 5,
        ]);

        $this->call([
            ProdukSeeder::class,
            PenjualanSeeder::class,
        ]);
    }
}
