<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanySubscription extends Model
{
    use HasFactory;

    // Karena menggunakan UUID sebagai primary key
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'company_id',
        'subscription_plan_id',
        'start_date',
        'end_date',
        'price_locked_monthly',
        'features_locked',
        'status',
        'last_billed_at',
        'next_billing_at',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'price_locked_monthly' => 'decimal:2',
        'features_locked' => 'array',
        'last_billed_at' => 'datetime',
        'next_billing_at' => 'datetime',
    ];

    /**
     * Get the company that owns the subscription.
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * Get the subscription plan for the company subscription.
     */
    public function subscriptionPlan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }

    /**
     * Get the affiliate recurring commissions for the subscription.
     */
    public function affiliateRecurringCommissions()
    {
        return $this->hasMany(AffiliateRecurringCommission::class, 'subscription_id');
    }
}