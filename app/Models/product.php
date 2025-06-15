<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'name','sku','description','price','category_id','is_available','slug'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
