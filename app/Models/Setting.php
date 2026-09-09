<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'store_name',
        'store_address',
        'store_phone',
        'store_logo',
        'currency',
        'low_stock_limit',
        'receipt_footer',
    ];

    protected $casts = [
        'low_stock_limit' => 'integer',
    ];

    public static function current(): self
    {
        return static::firstOrCreate([], [
            'store_name' => 'POS HADI',
            'currency' => 'IDR',
            'low_stock_limit' => 5,
            'receipt_footer' => 'Terima kasih telah berbelanja.',
        ]);
    }
}
