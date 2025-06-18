<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'description',
        'base_price_monthly',
        'base_price_yearly',
        'features_included',
        'is_active',
    ];

    protected $casts = [
        'features_included' => 'array', // Cast JSON column to array
        'is_active' => 'boolean',
        'base_price_monthly' => 'decimal:2',
        'base_price_yearly' => 'decimal:2',
    ];

    /**
     * Get the company subscriptions for the subscription plan.
     */
    public function companySubscriptions()
    {
        return $this->hasMany(CompanySubscription::class, 'subscription_plan_id');
    }
}