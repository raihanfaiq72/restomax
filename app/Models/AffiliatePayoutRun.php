<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliatePayoutRun extends Model
{
    use HasFactory;

    // Karena menggunakan UUID sebagai primary key
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'payout_date',
        'total_amount',
        'status',
        'processed_by_user_id',
        'notes',
    ];

    protected $casts = [
        'payout_date' => 'date',
        'total_amount' => 'decimal:2',
    ];

    /**
     * Get the user (Restomax Admin) who processed this payout run.
     */
    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by_user_id');
    }

    /**
     * Get the affiliate referrals included in this payout run.
     */
    public function affiliateReferrals()
    {
        return $this->hasMany(AffiliateReferral::class, 'payout_run_id');
    }

    /**
     * Get the affiliate recurring commissions included in this payout run.
     */
    public function affiliateRecurringCommissions()
    {
        return $this->hasMany(AffiliateRecurringCommission::class, 'payout_run_id');
    }
}