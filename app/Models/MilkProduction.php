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
        'afternoon_yield', // Add this
        'evening_yield',
        'lactation_number', 
        'days_in_milk', 
        'notes',
        'milker_id',
        'approved_by',
        'approved_at',
        'status' // pending, approved, rejected
    ];

    protected $casts = [
        'date' => 'date',
        'morning_yield' => 'decimal:2',
        'afternoon_yield' => 'decimal:2', // Add this
        'evening_yield' => 'decimal:2',
        'total_yield' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($milkProduction) {
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

        // If you want to update lactation_number automatically
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

    // Update the total_yield accessor
    public function getTotalYieldAttribute()
    {
        // Calculate total from all three sessions
        return ($this->morning_yield ?? 0) + 
               ($this->afternoon_yield ?? 0) + 
               ($this->evening_yield ?? 0);
    }

    // Add a method to get yields by session
    public function getYieldsBySession()
    {
        return [
            'morning' => $this->morning_yield ?? 0,
            'afternoon' => $this->afternoon_yield ?? 0,
            'evening' => $this->evening_yield ?? 0,
            'total' => $this->total_yield
        ];
    }

    // Add session-wise scopes
    public function scopeHasMorningYield($query)
    {
        return $query->where('morning_yield', '>', 0);
    }
    
    public function scopeHasAfternoonYield($query)
    {
        return $query->where('afternoon_yield', '>', 0);
    }
    
    public function scopeHasEveningYield($query)
    {
        return $query->where('evening_yield', '>', 0);
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