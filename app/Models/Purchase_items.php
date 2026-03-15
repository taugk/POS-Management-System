<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase_items extends Model
{
    protected $fillable = [
        'purchase_id',
        'product_id',
        'quantity',
        'cost_price',
        'subtotal',
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function product()
    {
        return $this->belongsTo(products::class);
    }
}
