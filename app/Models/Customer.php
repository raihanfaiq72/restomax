<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    // Karena menggunakan UUID sebagai primary key
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'company_id',
        'full_name',
        'email',
        'phone',
        'loyalty_points',
        'member_tier',
        'birthday',
    ];

    protected $casts = [
        'birthday' => 'date',
    ];

    /**
     * Get the company that the customer belongs to.
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * Get the orders placed by this customer.
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    /**
     * Get the reservations made by this customer.
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'customer_id');
    }
}