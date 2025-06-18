<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItemOption extends Model
{
    use HasFactory;

    // Karena menggunakan UUID sebagai primary key
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'order_item_id',
        'menu_item_option_value_id',
        'price_adjustment_applied',
    ];

    protected $casts = [
        'price_adjustment_applied' => 'decimal:2',
    ];

    /**
     * Get the order item that this option belongs to.
     */
    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id');
    }

    /**
     * Get the menu item option value that was selected.
     */
    public function optionValue()
    {
        return $this->belongsTo(MenuItemOptionValue::class, 'menu_item_option_value_id');
    }
}