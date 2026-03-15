<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sales extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'user_id',
        'total_price',
        'pay_amount',
        'tax_amount',
        'change_amount',
        'sale_date',
        'status',
        'notes',
        'promo_id',
    ];

    /**
     * Relasi ke User (Kasir/Staff yang melayani)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke detail item penjualan
     */
    public function items(): HasMany
    {
        return $this->hasMany(Sales_items::class, 'sale_id');
    }

    /**
     * Relasi ke Promo (jika ada)
     */
    public function promo(): BelongsTo
    {
        return $this->belongsTo(Promo::class);
    }
}