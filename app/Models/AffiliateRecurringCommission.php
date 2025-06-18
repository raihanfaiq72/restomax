<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliateRecurringCommission extends Model
{
    use HasFactory;

    // Karena menggunakan UUID sebagai primary key
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'affiliate_id',
        'referred_company_id',
        'subscription_id',
        'commission_amount',
        'billing_period_start',
        'billing_period_end',
        'payout_status',
        'payout_run_id',
    ];

    protected $casts = [
        'commission_amount' => 'decimal:2',
        'billing_period_start' => 'date',
        'billing_period_end' => 'date',
    ];

    /**
     * Get the affiliate who earned the commission.
     */
    public function affiliate()
    {
        return $this->belongsTo(Affiliate::class, 'affiliate_id');
    }

    /**
     * Get the company for which the commission was earned.
     */
    public function referredCompany()
    {
        return $this->belongsTo(Company::class, 'referred_company_id');
    }

    /**
     * Get the subscription that generated this recurring commission.
     */
    public function subscription()
    {
        return $this->belongsTo(CompanySubscription::class, 'subscription_id');
    }

    /**
     * Get the payout run this recurring commission belongs to.
     */
    public function payoutRun()
    {
        return $this->belongsTo(AffiliatePayoutRun::class, 'payout_run_id');
    }
}