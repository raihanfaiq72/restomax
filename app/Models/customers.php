<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class customers extends Model
{
    protected $table = 'customers';
    protected $fillable = [
        'name','slug','phone_number','email','birth_date','loyalty_tier','loyality_points'
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
