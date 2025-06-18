<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;

    // Karena menggunakan UUID sebagai primary key
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'outlet_id',
        'table_number',
        'capacity',
        'qr_code_url',
        'layout_x',
        'layout_y',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the outlet that the table belongs to.
     */
    public function outlet()
    {
        return $this->belongsTo(Outlet::class, 'outlet_id');
    }

    /**
     * Get the orders associated with this table.
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'table_id');
    }

    /**
     * Get the reservations for this table.
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'table_id');
    }
}