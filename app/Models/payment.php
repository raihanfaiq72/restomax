<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    // Karena menggunakan UUID sebagai primary key
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'order_id',
        'payment_method',
        'amount',
        'transaction_id',
        'status',
        'payment_time',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_time' => 'datetime',
    ];

    /**
     * Get the order that this payment belongs to.
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}