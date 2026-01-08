<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'company_name',
        'contact_person',
        'email',
        'phone',
        'address',
        'rate_per_liter',
        'payment_frequency',
        'contract_start_date',
        'contract_end_date',
        'bank_name',
        'bank_account',
        'tax_number',
        'status',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'rate_per_liter' => 'decimal:2',
        'contract_start_date' => 'date',
        'contract_end_date' => 'date',
    ];

    // Relationships
    public function milkSupplies()
    {
        return $this->hasMany(MilkSupply::class);
    }

    public function payments()
    {
        return $this->hasMany(SupplierPayment::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Get total milk supplied (approved supplies only)
    public function getTotalSuppliedAttribute()
    {
        return $this->milkSupplies()
            ->where('status', 'approved')
            ->sum('quantity_liters');
    }

    // Get total amount from approved milk supplies
    public function getTotalAmountAttribute()
    {
        return $this->milkSupplies()
            ->where('status', 'approved')
            ->sum('total_amount');
    }

    // Get total approved payments
    public function getTotalPaidAttribute()
    {
        return $this->payments()
            ->where('status', 'approved')
            ->sum('amount');
    }

    // Get total pending payments
    public function getTotalPendingAttribute()
    {
        return $this->payments()
            ->where('status', 'pending')
            ->sum('amount');
    }

    // Get balance due (approved supplies - approved payments)
    public function getBalanceAttribute()
    {
        return $this->total_amount - $this->total_paid;
    }

    // Get total pending balance (including pending payments)
    public function getPendingBalanceAttribute()
    {
        return $this->total_amount - $this->total_paid - $this->total_pending;
    }

    // Get unpaid milk supplies (with pending payments)
    public function getUnpaidSuppliesAttribute()
    {
        return $this->milkSupplies()
            ->where('status', 'approved')
            ->whereHas('payment', function($q) {
                $q->where('status', 'pending');
            })
            ->get();
    }

    // Get supplies with pending payments
    public function getSuppliesWithPendingPaymentAttribute()
    {
        return $this->milkSupplies()
            ->whereHas('payment', function($q) {
                $q->where('status', 'pending');
            })
            ->with('payment')
            ->get();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeHasPendingPayments($query)
    {
        return $query->whereHas('payments', function($q) {
            $q->where('status', 'pending');
        });
    }
}