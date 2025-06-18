<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Affiliate extends Model
{
    use HasFactory;

    // Karena menggunakan UUID sebagai primary key
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'user_id',
        'full_name',
        'email',
        'phone',
        'bank_account_number',
        'bank_name',
        'referral_code',
        'unique_link_token',
        'commission_rate',
        'is_active',
    ];

    protected $casts = [
        'commission_rate' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user associated with this affiliate profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the referral records made by this affiliate.
     */
    public function referrals()
    {
        return $this->hasMany(AffiliateReferral::class, 'affiliate_id');
    }

    /**
     * Get the recurring commissions earned by this affiliate.
     */
    public function recurringCommissions()
    {
        return $this->hasMany(AffiliateRecurringCommission::class, 'affiliate_id');
    }
}