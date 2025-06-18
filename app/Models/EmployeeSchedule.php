<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeSchedule extends Model
{
    use HasFactory;

    // Karena menggunakan UUID sebagai primary key
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'company_id',
        'user_id',
        'outlet_id',
        'schedule_date',
        'start_time',
        'end_time',
        'shift_type',
    ];

    protected $casts = [
        'schedule_date' => 'date',
        'start_time' => 'datetime', // Laravel will parse time strings into Carbon instances
        'end_time' => 'datetime',
    ];

    /**
     * Get the company that owns the schedule.
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * Get the user (employee) associated with the schedule.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the outlet where the schedule is for.
     */
    public function outlet()
    {
        return $this->belongsTo(Outlet::class, 'outlet_id');
    }
}