<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;

    // Karena menggunakan UUID sebagai primary key
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'company_id',
        'name',
        'unit_of_measure',
        'default_cost_per_unit',
    ];

    protected $casts = [
        'default_cost_per_unit' => 'decimal:2',
    ];

    /**
     * Get the company that owns the ingredient.
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * Get the inventory items for this ingredient.
     */
    public function inventoryItems()
    {
        return $this->hasMany(InventoryItem::class, 'ingredient_id');
    }

    /**
     * Get the recipes that use this ingredient.
     */
    public function recipes()
    {
        return $this->belongsToMany(Recipe::class, 'recipe_ingredients', 'ingredient_id', 'recipe_id');
    }

    /**
     * Get the inventory movements for this ingredient.
     */
    public function inventoryMovements()
    {
        return $this->hasMany(InventoryMovement::class, 'ingredient_id');
    }
}