<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'icon',
        'warna',
        'deskripsi',
        'status',
    ];

    public function produk()
    {
        return $this->hasMany(Produk::class);
    }
}