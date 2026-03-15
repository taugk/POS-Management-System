<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expenses extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_number',
        'name',
        'amount',
        'expense_date',
        'category',
        'notes',
        'user_id'
    ];

    // Relasi ke User (siapa yang mencatat)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}