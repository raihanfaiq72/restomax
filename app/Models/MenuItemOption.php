<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItemOption extends Model
{
    use HasFactory;

    // Karena menggunakan UUID sebagai primary key
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'company_id',
        'name',
        'type',
        'is_required',
    ];

    protected $casts = [
        'is_required' => 'boolean',
    ];

    /**
     * Get the company that owns the menu item option.
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * Get the values for the menu item option.
     */
    public function values()
    {
        return $this->hasMany(MenuItemOptionValue::class, 'menu_item_option_id');
    }

    /**
     * Get the menu items that use this option.
     */
    public function menuItems()
    {
        return $this->belongsToMany(MenuItem::class, 'menu_item_option_associations', 'menu_item_option_id', 'menu_item_id');
    }
}