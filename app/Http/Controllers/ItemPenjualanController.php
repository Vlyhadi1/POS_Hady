<?php

namespace App\Http\Controllers;

use App\Models\ItemPenjualan;
use App\Models\Penjualan;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ItemPenjualanController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:produk,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:999'],
        ]);

        $sale = Penjualan::firstOrCreate(
            ['user_id' => Auth::id(), 'status' => 'OPEN'],
            ['total_pembayaran' => 0, 'metode_pembayaran' => 'CASH']
        );

        try {
            DB::transaction(function () use ($data, $sale) {
                $product = Produk::lockForUpdate()->findOrFail($data['product_id']);

                if (!$product->status) {
                    throw new \RuntimeException('Produk sedang tidak aktif.');
                }

                if ($product->stok < $data['quantity']) {
                    throw new \RuntimeException("Stok {$product->nama} tidak mencukupi.");
                }

                $item = ItemPenjualan::where('penjualan_id', $sale->id)
                    ->where('produk_id', $product->id)
                    ->lockForUpdate()
                    ->first();

                if ($item) {
                    $item->kuantitas += $data['quantity'];
                } else {
                    $item = new ItemPenjualan([
                        'penjualan_id' => $sale->id,
                        'produk_id' => $product->id,
                        'kuantitas' => $data['quantity'],
                        'harga_satuan' => $product->harga_jual,
                    ]);
                }

                if ($item->kuantitas > $product->stok + ($item->getOriginal('kuantitas') ?? 0)) {
                    throw new \RuntimeException("Stok {$product->nama} tidak mencukupi.");
                }

                $product->decrement('stok', $data['quantity']);
                $item->subtotal = $item->kuantitas * $item->harga_satuan;
                $item->save();

                $sale->update([
                    'total_pembayaran' => $sale->itemPenjualan()->sum('subtotal'),
                ]);
            });
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }

        return back()->with('success', 'Produk ditambahkan ke keranjang.');
    }

    public function update(Request $request, ItemPenjualan $itempenjualan)
    {
        $this->authorize('update', $itempenjualan);

        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:999'],
        ]);

        $sale = $itempenjualan->penjualan;

        if (!$sale || $sale->status !== 'OPEN') {
            return back()->with('error', 'Transaksi sudah tidak dapat diubah.');
        }

        try {
            DB::transaction(function () use ($data, $itempenjualan, $sale) {
                $item = ItemPenjualan::lockForUpdate()->findOrFail($itempenjualan->id);
                $product = Produk::lockForUpdate()->findOrFail($item->produk_id);
                $difference = $data['quantity'] - $item->kuantitas;

                if ($difference > 0 && $product->stok < $difference) {
                    throw new \RuntimeException("Stok {$product->nama} tidak mencukupi.");
                }

                if ($difference > 0) $product->decrement('stok', $difference);
                if ($difference < 0) $product->increment('stok', abs($difference));

                $item->update([
                    'kuantitas' => $data['quantity'],
                    'subtotal' => $data['quantity'] * $item->harga_satuan,
                ]);

                $sale->update([
                    'total_pembayaran' => $sale->itemPenjualan()->sum('subtotal'),
                ]);
            });
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }

        return back()->with('success', 'Jumlah item diperbarui.');
    }

    public function destroy(ItemPenjualan $itempenjualan)
    {
        $this->authorize('delete', $itempenjualan);

        $sale = $itempenjualan->penjualan;
        if (!$sale || $sale->status !== 'OPEN') {
            return back()->with('error', 'Item pada transaksi selesai tidak dapat dihapus.');
        }

        DB::transaction(function () use ($itempenjualan, $sale) {
            $product = Produk::lockForUpdate()->find($itempenjualan->produk_id);
            if ($product) $product->increment('stok', $itempenjualan->kuantitas);

            $itempenjualan->delete();
            $sale->update([
                'total_pembayaran' => $sale->itemPenjualan()->sum('subtotal'),
            ]);
        });

        return back()->with('success', 'Item dihapus dari keranjang.');
    }
}
