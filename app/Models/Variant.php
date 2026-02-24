<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    protected $fillable = [
        'product_id', 'name', 'image', 'base_price', 'quantity_prices',
    ];

    protected $casts = [
        'quantity_prices' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
