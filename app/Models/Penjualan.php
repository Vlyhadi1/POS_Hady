<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User; // Pastikan namespace User diimport jika perlu

class Penjualan extends Model
{
    use HasFactory;

    protected $table = 'penjualan';

    protected $casts = [
        'total_pembayaran' => 'integer',
        'uang_dibayar' => 'integer',
        'kembalian' => 'integer',
    ];

    protected $fillable = [
        'user_id',
        'total_pembayaran',
        'uang_dibayar',
        'kembalian',
        'metode_pembayaran',
        'status'
    ];

    // Relasi ke User (Kasir)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function itemPenjualan()
    {
        return $this->hasMany(ItemPenjualan::class, 'penjualan_id');
    }
}