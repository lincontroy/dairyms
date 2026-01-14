<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'profile_picture',
        'email_notifications',
        'sms_notifications',
        'health_alerts',
        'is_active',
        'last_login_at',
        'last_login_ip',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'email_notifications' => 'boolean',
        'sms_notifications' => 'boolean',
        'health_alerts' => 'boolean',
    ];

    // Role checking methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isManager()
    {
        return $this->role === 'manager';
    }

    public function isVet()
    {
        return $this->role === 'vet';
    }

    public function isStaff()
    {
        return $this->role === 'staff';
    }

    public function hasRole($role)
    {
        return $this->role === $role;
    }

    // Check if user can approve milk records
    public function canApproveMilkRecords()
    {
        return in_array($this->role, ['admin', 'manager']);
    }

    // Check if user can manage health records
    public function canManageHealthRecords()
    {
        return in_array($this->role, ['admin', 'vet', 'manager']);
    }

  
    public function milkProductions()
    {
        return $this->hasMany(MilkProduction::class, 'milker_id');
        // Or if your foreign key is different:
        // return $this->hasMany(MilkProduction::class, 'user_id');
    }
    

    // Check if user can manage breeding records
    public function canManageBreedingRecords()
    {
        return in_array($this->role, ['admin', 'manager', 'vet']);
    }

    // Check if user can manage users
    public function canManageUsers()
    {
        return in_array($this->role, ['admin', 'manager']);
    }

    public function canManageSuppliers()
{
    return in_array($this->role, ['admin', 'manager']);
}

// Check if user can record milk supplies
public function canRecordMilkSupply()
{
    return in_array($this->role, ['manager', 'admin']);
}

// Check if user can approve payments
public function canApprovePayments()
{
    return $this->role === 'admin';
}

// Add relationship
public function recordedSupplies()
{
    return $this->hasMany(MilkSupply::class, 'recorded_by');
}

public function supplierPayments()
{
    return $this->hasMany(SupplierPayment::class, 'created_by');
}

// Add to the User model after other permission methods

// Check if user can manage expenses
public function canManageExpenses()
{
    return in_array($this->role, ['admin', 'manager']);
}

// Check if user can view expense totals
public function canViewExpenseTotals()
{
    return $this->role === 'admin';
}

// Check if user can approve expenses
public function canApproveExpenses()
{
    return $this->role === 'admin';
}

// Expense relationship
public function expenses()
{
    return $this->hasMany(Expense::class);
}

}