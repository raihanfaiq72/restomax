<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItemOptionValue extends Model
{
    use HasFactory;

    // Karena menggunakan UUID sebagai primary key
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'menu_item_option_id',
        'value',
        'price_adjustment',
    ];

    protected $casts = [
        'price_adjustment' => 'decimal:2',
    ];

    /**
     * Get the menu item option that this value belongs to.
     */
    public function option()
    {
        return $this->belongsTo(MenuItemOption::class, 'menu_item_option_id');
    }

    /**
     * Get the order item options that use this value.
     */
    public function orderItemOptions()
    {
        return $this->hasMany(OrderItemOption::class, 'menu_item_option_value_id');
    }
}