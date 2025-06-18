<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    use HasFactory;

    // Karena menggunakan UUID sebagai primary key
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'company_id',
        'outlet_id',
        'ingredient_id',
        'current_stock',
        'minimum_stock_level',
        'last_restocked_at',
    ];

    protected $casts = [
        'current_stock' => 'decimal:2',
        'minimum_stock_level' => 'decimal:2',
        'last_restocked_at' => 'datetime',
    ];

    /**
     * Get the company that owns the inventory item.
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * Get the outlet that the inventory item belongs to.
     */
    public function outlet()
    {
        return $this->belongsTo(Outlet::class, 'outlet_id');
    }

    /**
     * Get the ingredient associated with this inventory item.
     */
    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class, 'ingredient_id');
    }
}