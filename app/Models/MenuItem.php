<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;

    // Karena menggunakan UUID sebagai primary key
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'company_id',
        'outlet_id',
        'category_id',
        'name',
        'description',
        'price',
        'image_url',
        'is_available',
        'preparation_time_minutes',
        'sku',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_available' => 'boolean',
    ];

    /**
     * Get the company that owns the menu item.
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * Get the outlet that the menu item belongs to.
     */
    public function outlet()
    {
        return $this->belongsTo(Outlet::class, 'outlet_id');
    }

    /**
     * Get the category that the menu item belongs to.
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Get the order items that include this menu item.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'menu_item_id');
    }

    /**
     * Get the menu item options associated with this menu item.
     */
    public function options()
    {
        return $this->belongsToMany(MenuItemOption::class, 'menu_item_option_associations', 'menu_item_id', 'menu_item_option_id');
    }

    /**
     * Get the recipe for the menu item.
     */
    public function recipe()
    {
        return $this->hasOne(Recipe::class, 'menu_item_id');
    }
}