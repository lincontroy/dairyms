<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MilkSupply extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'date',
        'quantity_liters',
        'rate_per_liter',
        'total_amount',
        'waste_liters',
        'notes',
        'supplied_by',
        'recorded_by',
        'status',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'date' => 'date',
        'quantity_liters' => 'decimal:2',
        'rate_per_liter' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'waste_liters' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($milkSupply) {
            $milkSupply->recorded_by = auth()->id();
            $milkSupply->status = 'recorded';
            
            if (!$milkSupply->rate_per_liter && $milkSupply->supplier) {
                $milkSupply->rate_per_liter = $milkSupply->supplier->rate_per_liter;
            }
            
            if ($milkSupply->quantity_liters && $milkSupply->rate_per_liter) {
                $milkSupply->total_amount = $milkSupply->quantity_liters * $milkSupply->rate_per_liter;
            }
        });

        static::created(function ($milkSupply) {
            if (!$milkSupply->payment()->exists()) {
                $payment = new \App\Models\SupplierPayment();
                $payment->supplier_id = $milkSupply->supplier_id;
                $payment->milk_supply_id = $milkSupply->id;
                $payment->payment_date = now();
                $payment->amount = $milkSupply->total_amount;
                $payment->payment_method = 'bank_transfer';
                $payment->payment_period_start = $milkSupply->date;
                $payment->payment_period_end = $milkSupply->date;
                $payment->notes = 'Auto-generated payment for milk supply on ' . $milkSupply->date->format('Y-m-d');
                $payment->status = 'pending';
                $payment->created_by = $milkSupply->recorded_by;
                
                // Generate reference number
                $payment->reference_number = 'PAY-' . strtoupper(uniqid());
                
                $payment->save();
            }
        });
    }

    // Relationships
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function supplierUser()
    {
        return $this->belongsTo(User::class, 'supplied_by');
    }

    public function payment()
    {
        return $this->hasOne(SupplierPayment::class);
    }

    public function payments()
    {
        return $this->hasMany(SupplierPayment::class);
    }

    // Attributes
    public function getNetQuantityAttribute()
    {
        return $this->quantity_liters - $this->waste_liters;
    }

    public function hasPayment()
    {
        return $this->payment()->exists();
    }

    public function hasApprovedPayment()
    {
        return $this->payment()->where('status', 'approved')->exists();
    }

    public function getPaymentStatusAttribute()
    {
        if (!$this->payment) {
            return 'no_payment';
        }
        return $this->payment->status;
    }

    // Status checks
    public function isRecorded()
    {
        return $this->status === 'recorded';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    public function isPaymentPending()
    {
        return $this->payment && $this->payment->status === 'pending';
    }

    public function isPaymentApproved()
    {
        return $this->payment && $this->payment->status === 'approved';
    }

    public function isPaymentRejected()
    {
        return $this->payment && $this->payment->status === 'rejected';
    }

    // ==================== SCOPES ====================
    
    /**
     * Scope for today's milk supplies
     */
    public function scopeToday($query)
    {
        return $query->whereDate('date', today());
    }

    /**
     * Scope for this week's milk supplies
     */
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    /**
     * Scope for this month's milk supplies
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('date', now()->month)
                     ->whereYear('date', now()->year);
    }

    /**
     * Scope for specific month and year
     */
    public function scopeForMonth($query, $year, $month)
    {
        return $query->whereYear('date', $year)
                     ->whereMonth('date', $month);
    }

    /**
     * Scope for date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    /**
     * Scope for recorded milk supplies
     */
    public function scopeRecorded($query)
    {
        return $query->where('status', 'recorded');
    }

    /**
     * Scope for approved milk supplies
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for milk supplies with pending payment
     */
    public function scopeWithPendingPayment($query)
    {
        return $query->whereHas('payment', function($q) {
            $q->where('status', 'pending');
        });
    }

    /**
     * Scope for milk supplies with approved payment
     */
    public function scopeWithApprovedPayment($query)
    {
        return $query->whereHas('payment', function($q) {
            $q->where('status', 'approved');
        });
    }

    /**
     * Scope for milk supplies without payment
     */
    public function scopeWithoutPayment($query)
    {
        return $query->whereDoesntHave('payment');
    }

    /**
     * Scope for specific supplier
     */
    public function scopeSupplier($query, $supplierId)
    {
        return $query->where('supplier_id', $supplierId);
    }

    /**
     * Scope for milk supplies with waste
     */
    public function scopeHasWaste($query)
    {
        return $query->where('waste_liters', '>', 0);
    }
}