<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\ItemPenjualan;
use App\Models\Penjualan;
use App\Models\Produk;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PenjualanTest extends TestCase
{
    use RefreshDatabase;

    public function test_kasir_menambahkan_produk_dan_stok_berkurang(): void
    {
        $kasirRole = Role::create(['name' => 'kasir']);
        $kasir = User::factory()->create(['role_id' => $kasirRole->id]);
        $category = Category::create(['nama' => 'Minuman']);
        $product = Produk::create([
            'user_id' => $kasir->id,
            'category_id' => $category->id,
            'nama' => 'Air Mineral',
            'harga_beli' => 2000,
            'harga_jual' => 3000,
            'stok' => 10,
            'satuan' => 'Botol',
            'minimum_stok' => 2,
            'status' => true,
        ]);

        $response = $this->actingAs($kasir)->post(route('itempenjualan.store'), [
            'product_id' => $product->id,
            'quantity' => 3,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('produk', [
            'id' => $product->id,
            'stok' => 7,
        ]);
        $this->assertDatabaseHas('item_penjualan', [
            'produk_id' => $product->id,
            'kuantitas' => 3,
            'subtotal' => 9000,
        ]);
    }

    public function test_kasir_tidak_bisa_melihat_transaksi_kasir_lain(): void
    {
        $kasirRole = Role::create(['name' => 'kasir']);
        $owner = User::factory()->create(['role_id' => $kasirRole->id]);
        $otherKasir = User::factory()->create(['role_id' => $kasirRole->id]);
        $sale = Penjualan::create([
            'user_id' => $owner->id,
            'total_pembayaran' => 0,
            'metode_pembayaran' => 'CASH',
            'status' => 'OPEN',
        ]);

        $response = $this->actingAs($otherKasir)->get(route('penjualan.show', $sale));

        $response->assertForbidden();
    }

    public function test_checkout_mengurangi_diskon_dari_total(): void
    {
        $adminRole = Role::create(['name' => 'admin']);
        $kasir = User::factory()->create(['role_id' => $adminRole->id]);
        $product = Produk::create([
            'user_id' => $kasir->id,
            'nama' => 'Kopi',
            'harga_beli' => 5000,
            'harga_jual' => 10000,
            'stok' => 8,
            'satuan' => 'Pcs',
            'minimum_stok' => 1,
            'status' => true,
        ]);
        $sale = Penjualan::create([
            'user_id' => $kasir->id,
            'total_pembayaran' => 10000,
            'metode_pembayaran' => 'CASH',
            'status' => 'OPEN',
        ]);
        ItemPenjualan::create([
            'penjualan_id' => $sale->id,
            'produk_id' => $product->id,
            'kuantitas' => 1,
            'harga_satuan' => 10000,
            'subtotal' => 10000,
        ]);

        $response = $this->actingAs($kasir)->put(route('penjualan.update', $sale), [
            'payment_method' => 'CASH',
            'amount_paid' => 9000,
            'diskon' => 1500,
        ]);

        $response->assertRedirect(route('penjualan.show', $sale));
        $this->assertDatabaseHas('penjualan', [
            'id' => $sale->id,
            'diskon' => 1500,
            'total_pembayaran' => 8500,
            'uang_dibayar' => 9000,
            'kembalian' => 500,
            'status' => 'COMPLETED',
        ]);
    }

    public function test_membatalkan_transaksi_mengembalikan_stok(): void
    {
        $kasirRole = Role::create(['name' => 'kasir']);
        $kasir = User::factory()->create(['role_id' => $kasirRole->id]);
        $product = Produk::create([
            'user_id' => $kasir->id,
            'nama' => 'Pulpen',
            'harga_beli' => 2000,
            'harga_jual' => 3500,
            'stok' => 4,
            'satuan' => 'Pcs',
            'minimum_stok' => 1,
            'status' => true,
        ]);
        $sale = Penjualan::create([
            'user_id' => $kasir->id,
            'total_pembayaran' => 7000,
            'metode_pembayaran' => 'CASH',
            'status' => 'OPEN',
        ]);
        ItemPenjualan::create([
            'penjualan_id' => $sale->id,
            'produk_id' => $product->id,
            'kuantitas' => 2,
            'harga_satuan' => 3500,
            'subtotal' => 7000,
        ]);
        $product->decrement('stok', 2);

        $response = $this->actingAs($kasir)->delete(route('penjualan.destroy', $sale));

        $response->assertRedirect(route('penjualan.index'));
        $this->assertDatabaseHas('produk', ['id' => $product->id, 'stok' => 4]);
        $this->assertDatabaseHas('penjualan', ['id' => $sale->id, 'status' => 'CANCELLED']);
    }

    public function test_laporan_pdf_dapat_diunduh_kasir(): void
    {
        $kasirRole = Role::create(['name' => 'kasir']);
        $kasir = User::factory()->create(['role_id' => $kasirRole->id]);

        $response = $this->actingAs($kasir)->get(route('laporan.pdf', [
            'from' => now()->format('Y-m-d'),
            'to' => now()->format('Y-m-d'),
        ]));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_laporan_excel_dapat_diunduh_kasir(): void
    {
        $kasirRole = Role::create(['name' => 'kasir']);
        $kasir = User::factory()->create(['role_id' => $kasirRole->id]);

        $response = $this->actingAs($kasir)->get(route('laporan.excel', [
            'from' => now()->format('Y-m-d'),
            'to' => now()->format('Y-m-d'),
        ]));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    public function test_kasir_tidak_dapat_menggunakan_diskon(): void
    {
        $kasirRole = Role::create(['name' => 'kasir']);
        $kasir = User::factory()->create(['role_id' => $kasirRole->id]);
        $product = Produk::create([
            'user_id' => $kasir->id, 'nama' => 'Buku', 'harga_beli' => 5000,
            'harga_jual' => 8000, 'stok' => 3, 'satuan' => 'Pcs', 'minimum_stok' => 1, 'status' => true,
        ]);
        $sale = Penjualan::create([
            'user_id' => $kasir->id, 'total_pembayaran' => 8000,
            'metode_pembayaran' => 'CASH', 'status' => 'OPEN',
        ]);
        ItemPenjualan::create([
            'penjualan_id' => $sale->id, 'produk_id' => $product->id,
            'kuantitas' => 1, 'harga_satuan' => 8000, 'subtotal' => 8000,
        ]);

        $response = $this->actingAs($kasir)->put(route('penjualan.update', $sale), [
            'payment_method' => 'CASH', 'amount_paid' => 8000, 'diskon' => 1000,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Diskon hanya dapat digunakan oleh Admin.');
        $this->assertDatabaseHas('penjualan', ['id' => $sale->id, 'status' => 'OPEN', 'diskon' => 0]);
    }

    public function test_admin_dapat_menggunakan_diskon_persentase(): void
    {
        $adminRole = Role::create(['name' => 'admin']);
        $admin = User::factory()->create(['role_id' => $adminRole->id]);
        $product = Produk::create([
            'user_id' => $admin->id, 'nama' => 'Tas', 'harga_beli' => 50000,
            'harga_jual' => 100000, 'stok' => 2, 'satuan' => 'Pcs', 'minimum_stok' => 1, 'status' => true,
        ]);
        $sale = Penjualan::create([
            'user_id' => $admin->id, 'total_pembayaran' => 100000,
            'metode_pembayaran' => 'CASH', 'status' => 'OPEN',
        ]);
        ItemPenjualan::create([
            'penjualan_id' => $sale->id, 'produk_id' => $product->id,
            'kuantitas' => 1, 'harga_satuan' => 100000, 'subtotal' => 100000,
        ]);

        $response = $this->actingAs($admin)->put(route('penjualan.update', $sale), [
            'payment_method' => 'CASH', 'amount_paid' => 90000,
            'diskon_type' => 'persen', 'diskon_value' => 10,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('penjualan', [
            'id' => $sale->id, 'diskon' => 10000, 'diskon_persen' => 10,
            'total_pembayaran' => 90000, 'status' => 'COMPLETED',
        ]);
    }

    public function test_nota_pdf_dapat_diunduh_pemilik_transaksi(): void
    {
        $kasirRole = Role::create(['name' => 'kasir']);
        $kasir = User::factory()->create(['role_id' => $kasirRole->id]);
        $sale = Penjualan::create([
            'user_id' => $kasir->id, 'total_pembayaran' => 0,
            'metode_pembayaran' => 'CASH', 'status' => 'OPEN',
        ]);

        $response = $this->actingAs($kasir)->get(route('penjualan.pdf', $sale));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
    }
}
