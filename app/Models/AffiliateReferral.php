<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliateReferral extends Model
{
    use HasFactory;

    // Karena menggunakan UUID sebagai primary key
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'affiliate_id',
        'referred_company_id',
        'referral_date',
        'commission_percentage_applied',
        'calculated_commission_amount',
        'payout_status',
        'payout_run_id',
    ];

    protected $casts = [
        'referral_date' => 'datetime',
        'commission_percentage_applied' => 'decimal:2',
        'calculated_commission_amount' => 'decimal:2',
    ];

    /**
     * Get the affiliate who made the referral.
     */
    public function affiliate()
    {
        return $this->belongsTo(Affiliate::class, 'affiliate_id');
    }

    /**
     * Get the company that was referred.
     */
    public function referredCompany()
    {
        return $this->belongsTo(Company::class, 'referred_company_id');
    }

    /**
     * Get the payout run this referral commission belongs to.
     */
    public function payoutRun()
    {
        return $this->belongsTo(AffiliatePayoutRun::class, 'payout_run_id');
    }
}