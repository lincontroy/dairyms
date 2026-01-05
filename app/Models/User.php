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
}