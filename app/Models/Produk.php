<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';

    protected $fillable = [
        'user_id',
        'category_id',
        'foto',
        'nama',
        'harga_beli',
        'harga_jual',
        'stok',
        'satuan',
        'minimum_stok',
        'deskripsi',
        'status',
    ];

    protected $casts = [
        'harga_beli' => 'integer',
        'harga_jual' => 'integer',
        'stok' => 'integer',
        'minimum_stok' => 'integer',

        'status' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relasi User
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Relasi Category
    |--------------------------------------------------------------------------
    */

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function itemPenjualan()
    {
        return $this->hasMany(ItemPenjualan::class, 'produk_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessor Profit
    |--------------------------------------------------------------------------
    */

    public function getProfitAttribute()
    {
        return $this->harga_jual - $this->harga_beli;
    }
}