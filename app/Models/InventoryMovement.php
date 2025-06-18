<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryMovement extends Model
{
    use HasFactory;

    // Karena menggunakan UUID sebagai primary key
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'company_id',
        'ingredient_id',
        'outlet_id_from',
        'outlet_id_to',
        'quantity',
        'type',
        'reference_id',
        'notes',
        'movement_at',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'movement_at' => 'datetime',
    ];

    /**
     * Get the company that owns the inventory movement.
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * Get the ingredient associated with this movement.
     */
    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class, 'ingredient_id');
    }

    /**
     * Get the outlet from which the inventory moved.
     */
    public function outletFrom()
    {
        return $this->belongsTo(Outlet::class, 'outlet_id_from');
    }

    /**
     * Get the outlet to which the inventory moved.
     */
    public function outletTo()
    {
        return $this->belongsTo(Outlet::class, 'outlet_id_to');
    }
}