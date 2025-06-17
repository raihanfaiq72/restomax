<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ingredient extends Model
{
    protected $table = 'ingredients';

    protected $fillable = [
        'name','slug','stock_quantity','unit','low_stock_threshold'
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function products()
    {
        // bahan baku bisa digunakan oleh banyak produk
        return $this->belongsToMany(Product::class, 'recipes')->withPivot('quantity_needed');
    }
}
