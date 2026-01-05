<?php
// app/Models/MilkProduction.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MilkProduction extends Model
{
    use HasFactory;

    protected $fillable = [
        'animal_id', 
        'date', 
        'morning_yield', 
        'evening_yield',
        'lactation_number', 
        'days_in_milk', 
        'total_yield',
        'notes',
        'milker_id',
        'approved_by',
        'approved_at',
        'status' // pending, approved, rejected
    ];

    protected $casts = [
        'date' => 'date',
        'morning_yield' => 'decimal:2',
        'evening_yield' => 'decimal:2',
        'total_yield' => 'decimal:2',
        'approved_at' => 'datetime',
        'email_notifications' => 'boolean',
        'sms_notifications' => 'boolean',
        'health_alerts' => 'boolean',
    ];

    protected static function booted()
    {
        static::creating(function ($milkProduction) {
            $milkProduction->total_yield = 
                ($milkProduction->morning_yield ?? 0) + 
                ($milkProduction->evening_yield ?? 0);
            
            // Set default status based on user role
            if (auth()->check()) {
                $milkProduction->milker_id = auth()->id();
                
                // Staff records need approval, managers/admins are auto-approved
                if (auth()->user()->canApproveMilkRecords()) {
                    $milkProduction->status = 'approved';
                    $milkProduction->approved_by = auth()->id();
                    $milkProduction->approved_at = now();
                } else {
                    $milkProduction->status = 'pending';
                }
            }
        });
    }

    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }

    public function milker()
    {
        return $this->belongsTo(User::class, 'milker_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Scope for approved records
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    // Scope for pending records
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Scope for today's milk records
    public function scopeToday($query)
    {
        return $query->whereDate('date', today());
    }

    // Scope for current month
    public function scopeCurrentMonth($query)
    {
        return $query->whereMonth('date', now()->month)
                     ->whereYear('date', now()->year);
    }

    // Check if record is approved
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    // Check if record is pending
    public function isPending()
    {
        return $this->status === 'pending';
    }

    // Check if record is rejected
    public function isRejected()
    {
        return $this->status === 'rejected';
    }
}