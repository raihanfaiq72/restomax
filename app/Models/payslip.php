<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaySlip extends Model
{
    use HasFactory;

    // Karena menggunakan UUID sebagai primary key
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'payroll_run_id',
        'user_id',
        'base_salary',
        'hourly_wage',
        'hours_worked',
        'overtime_hours',
        'overtime_pay',
        'allowances_json',
        'deductions_json',
        'net_pay',
        'slip_generated_at',
    ];

    protected $casts = [
        'base_salary' => 'decimal:2',
        'hourly_wage' => 'decimal:2',
        'hours_worked' => 'decimal:2',
        'overtime_hours' => 'decimal:2',
        'overtime_pay' => 'decimal:2',
        'allowances_json' => 'array',
        'deductions_json' => 'array',
        'net_pay' => 'decimal:2',
        'slip_generated_at' => 'datetime',
    ];

    /**
     * Get the payroll run that this pay slip belongs to.
     */
    public function payrollRun()
    {
        return $this->belongsTo(PayrollRun::class, 'payroll_run_id');
    }

    /**
     * Get the user (employee) that this pay slip is for.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}