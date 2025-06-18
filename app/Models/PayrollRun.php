<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollRun extends Model
{
    use HasFactory;

    // Karena menggunakan UUID sebagai primary key
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'company_id',
        'payroll_start_date',
        'payroll_end_date',
        'status',
        'processed_by_user_id',
        'total_payout_amount',
        'notes',
    ];

    protected $casts = [
        'payroll_start_date' => 'date',
        'payroll_end_date' => 'date',
        'total_payout_amount' => 'decimal:2',
    ];

    /**
     * Get the company that owns the payroll run.
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * Get the user who processed this payroll run.
     */
    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by_user_id');
    }

    /**
     * Get the pay slips generated for this payroll run.
     */
    public function paySlips()
    {
        return $this->hasMany(PaySlip::class, 'payroll_run_id');
    }
}