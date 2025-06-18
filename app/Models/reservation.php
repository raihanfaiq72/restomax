<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    // Karena menggunakan UUID sebagai primary key
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'company_id',
        'outlet_id',
        'customer_id',
        'table_id',
        'reservation_time',
        'end_time',
        'number_of_guests',
        'status',
        'notes',
        'deposit_amount',
        'confirmation_token',
    ];

    protected $casts = [
        'reservation_time' => 'datetime',
        'end_time' => 'datetime',
        'deposit_amount' => 'decimal:2',
    ];

    /**
     * Get the company that owns the reservation.
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * Get the outlet where the reservation is made.
     */
    public function outlet()
    {
        return $this->belongsTo(Outlet::class, 'outlet_id');
    }

    /**
     * Get the customer who made the reservation.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     * Get the table reserved.
     */
    public function table()
    {
        return $this->belongsTo(Table::class, 'table_id');
    }
}