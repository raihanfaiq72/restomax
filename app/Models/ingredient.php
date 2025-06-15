<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ingredient extends Model
{
    protected $table = 'ingredients';

    protected $fillable = [
        'name','slug','stock_quantity','unit','low_stock_threshold'
    ];
}
