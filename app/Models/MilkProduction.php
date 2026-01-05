<?php
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
        // REMOVE 'total_yield' from fillable
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
        'total_yield' => 'decimal:2', // Keep this cast
        'approved_at' => 'datetime',
    ];

    // Remove the booted() method or modify it
    protected static function booted()
    {
        static::creating(function ($milkProduction) {
            // REMOVE this line: $milkProduction->total_yield = ...
            
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

        // Optional: If you want to update lactation_number or days_in_milk automatically
        static::creating(function ($milkProduction) {
            if (!$milkProduction->lactation_number && $milkProduction->animal) {
                // Get the latest lactation number for this animal
                $latest = self::where('animal_id', $milkProduction->animal_id)
                    ->latest('date')
                    ->first();
                
                $milkProduction->lactation_number = $latest ? $latest->lactation_number : 1;
            }
        });
    }

    // Add an accessor for total_yield if you still want to use it in code
    public function getTotalYieldAttribute()
    {
        // If the database has a generated column, it will use that
        // Otherwise, calculate it
        return $this->attributes['total_yield'] ?? 
               (($this->morning_yield ?? 0) + ($this->evening_yield ?? 0));
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

    // Add a method to calculate days in milk
    public function calculateDaysInMilk()
    {
        if ($this->animal && $this->date) {
            // Find the calving date for this lactation
            // This would need to be implemented based on your breeding records
            // For now, return null or calculate based on some logic
            return null;
        }
        return null;
    }
}