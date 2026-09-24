<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $fillable = [
        'produk_id', 'user_id', 'stok_sebelum', 'perubahan', 'stok_sesudah', 'jenis', 'catatan',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}