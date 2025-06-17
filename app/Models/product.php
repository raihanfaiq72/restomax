<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'name','sku','description','price','category_id','is_available','slug'
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function ingredients()
    {
        // produk memiliki banyak bahan baku dari tabel 'recipes'
        return $this->belongsToMany(Ingredient::class, 'recipes')->withPivot('quantity_needed');
    }
}
