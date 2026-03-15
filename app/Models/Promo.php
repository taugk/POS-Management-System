<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Promo extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'type', 'discount_amount', 'max_discount', 
        'min_purchase', 'start_date', 'end_date', 'is_active', 
        'usage_limit', 'used_count'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function isValid(): bool
    {
        $now = now();
        if (!$this->is_active) return false;
        
        // Cek rentang waktu sampai detik terakhir di hari berakhir
        if ($now < $this->start_date->startOfDay() || $now > $this->end_date->endOfDay()) {
            return false;
        }

        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    public function calculateDiscount($subtotal): float
    {
        if ($subtotal < $this->min_purchase) return 0;

        $discount = 0;
        if ($this->type === 'fixed') {
            $discount = $this->discount_amount;
        } else {
            $discount = ($subtotal * $this->discount_amount) / 100;
            if ($this->max_discount && $discount > $this->max_discount) {
                $discount = $this->max_discount;
            }
        }
        return min($discount, $subtotal);
    }

    /**
     * Menambah jumlah penggunaan dan otomatis menonaktifkan jika limit tercapai.
     */
    public function incrementUsage()
    {
        $this->used_count += 1;

        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) {
            $this->is_active = false;
        }

        return $this->save();
    }

    public function sales()
    {
        return $this->hasMany(Sales::class, 'promo_id');
    }
}