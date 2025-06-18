<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Karena menggunakan UUID sebagai primary key
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'company_id',
        'email',
        'password',
        'full_name',
        'phone',
        'profile_picture_url',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the company that the user belongs to.
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * Get the roles that are assigned to the user.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id')
                    ->withPivot('company_id'); // If you need the company_id from the pivot table
    }

    /**
     * Get the orders associated with the user (as cashier/waiter).
     */
    public function ordersAsCashier()
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    /**
     * Get the orders associated with the user (as delivery driver).
     */
    public function ordersAsDeliveryDriver()
    {
        return $this->hasMany(Order::class, 'delivery_driver_id');
    }

    /**
     * Get the employee attendances for the user.
     */
    public function employeeAttendances()
    {
        return $this->hasMany(EmployeeAttendance::class, 'user_id');
    }

    /**
     * Get the employee schedules for the user.
     */
    public function employeeSchedules()
    {
        return $this->hasMany(EmployeeSchedule::class, 'user_id');
    }

    /**
     * Get the payroll runs processed by this user.
     */
    public function processedPayrollRuns()
    {
        return $this->hasMany(PayrollRun::class, 'processed_by_user_id');
    }

    /**
     * Get the pay slips for the user.
     */
    public function paySlips()
    {
        return $this->hasMany(PaySlip::class, 'user_id');
    }

    /**
     * Get the affiliate profile if this user is an affiliate.
     */
    public function affiliate()
    {
        return $this->hasOne(Affiliate::class, 'user_id');
    }

    /**
     * Get the affiliate payout runs processed by this user (Restomax Admin).
     */
    public function processedAffiliatePayoutRuns()
    {
        return $this->hasMany(AffiliatePayoutRun::class, 'processed_by_user_id');
    }
}