<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    // Karena menggunakan UUID sebagai primary key
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'business_type',
        'scale',
        'address',
        'phone',
        'email',
        'logo_url',
        'operating_hours',
        'current_subscription_id',
        'is_active',
    ];

    protected $casts = [
        'operating_hours' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the current subscription for the company.
     */
    public function currentSubscription()
    {
        return $this->belongsTo(CompanySubscription::class, 'current_subscription_id');
    }

    /**
     * Get the users for the company.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'company_id');
    }

    /**
     * Get the company subscriptions for the company.
     */
    public function companySubscriptions()
    {
        return $this->hasMany(CompanySubscription::class, 'company_id');
    }

    /**
     * Get the outlets for the company.
     */
    public function outlets()
    {
        return $this->hasMany(Outlet::class, 'company_id');
    }

    /**
     * Get the categories for the company.
     */
    public function categories()
    {
        return $this->hasMany(Category::class, 'company_id');
    }

    /**
     * Get the menu items for the company.
     */
    public function menuItems()
    {
        return $this->hasMany(MenuItem::class, 'company_id');
    }

    /**
     * Get the menu item options for the company.
     */
    public function menuItemOptions()
    {
        return $this->hasMany(MenuItemOption::class, 'company_id');
    }

    /**
     * Get the customers for the company.
     */
    public function customers()
    {
        return $this->hasMany(Customer::class, 'company_id');
    }

    /**
     * Get the orders for the company.
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'company_id');
    }

    /**
     * Get the ingredients for the company.
     */
    public function ingredients()
    {
        return $this->hasMany(Ingredient::class, 'company_id');
    }

    /**
     * Get the inventory items for the company.
     */
    public function inventoryItems()
    {
        return $this->hasMany(InventoryItem::class, 'company_id');
    }

    /**
     * Get the recipes for the company.
     */
    public function recipes()
    {
        return $this->hasMany(Recipe::class, 'company_id');
    }

    /**
     * Get the inventory movements for the company.
     */
    public function inventoryMovements()
    {
        return $this->hasMany(InventoryMovement::class, 'company_id');
    }

    /**
     * Get the employee attendances for the company.
     */
    public function employeeAttendances()
    {
        return $this->hasMany(EmployeeAttendance::class, 'company_id');
    }

    /**
     * Get the employee schedules for the company.
     */
    public function employeeSchedules()
    {
        return $this->hasMany(EmployeeSchedule::class, 'company_id');
    }

    /**
     * Get the payroll runs for the company.
     */
    public function payrollRuns()
    {
        return $this->hasMany(PayrollRun::class, 'company_id');
    }

    /**
     * Get the reservations for the company.
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'company_id');
    }

    /**
     * Get the expenses for the company.
     */
    public function expenses()
    {
        return $this->hasMany(Expense::class, 'company_id');
    }

    /**
     * Get the affiliate referrals for the company.
     */
    public function affiliateReferrals()
    {
        return $this->hasMany(AffiliateReferral::class, 'referred_company_id');
    }

    /**
     * Get the affiliate recurring commissions for the company.
     */
    public function affiliateRecurringCommissions()
    {
        return $this->hasMany(AffiliateRecurringCommission::class, 'referred_company_id');
    }
}