<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Outlet extends Model
{
    use HasFactory;

    // Karena menggunakan UUID sebagai primary key
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'company_id',
        'name',
        'address',
        'phone',
        'email',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the company that owns the outlet.
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * Get the categories for the outlet.
     */
    public function categories()
    {
        return $this->hasMany(Category::class, 'outlet_id');
    }

    /**
     * Get the menu items for the outlet.
     */
    public function menuItems()
    {
        return $this->hasMany(MenuItem::class, 'outlet_id');
    }

    /**
     * Get the tables for the outlet.
     */
    public function tables()
    {
        return $this->hasMany(Table::class, 'outlet_id');
    }

    /**
     * Get the orders for the outlet.
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'outlet_id');
    }

    /**
     * Get the inventory items for the outlet.
     */
    public function inventoryItems()
    {
        return $this->hasMany(InventoryItem::class, 'outlet_id');
    }

    /**
     * Get the inventory movements from this outlet.
     */
    public function inventoryMovementsFrom()
    {
        return $this->hasMany(InventoryMovement::class, 'outlet_id_from');
    }

    /**
     * Get the inventory movements to this outlet.
     */
    public function inventoryMovementsTo()
    {
        return $this->hasMany(InventoryMovement::class, 'outlet_id_to');
    }

    /**
     * Get the employee attendances for the outlet.
     */
    public function employeeAttendances()
    {
        return $this->hasMany(EmployeeAttendance::class, 'outlet_id');
    }

    /**
     * Get the employee schedules for the outlet.
     */
    public function employeeSchedules()
    {
        return $this->hasMany(EmployeeSchedule::class, 'outlet_id');
    }

    /**
     * Get the reservations for the outlet.
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'outlet_id');
    }

    /**
     * Get the expenses for the outlet.
     */
    public function expenses()
    {
        return $this->hasMany(Expense::class, 'outlet_id');
    }
}