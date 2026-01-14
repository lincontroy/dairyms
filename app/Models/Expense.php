<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'amount',
        'category',
        'description',
        'payment_method',
        'reference_number',
        'user_id',
        'supplier_id',
        'status',
        'notes'
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeForDate($query, $date)
    {
        return $query->whereDate('date', $date);
    }

    public function scopeForMonth($query, $month = null)
    {
        if (!$month) {
            $month = now();
        }
        return $query->whereYear('date', $month->year)
                     ->whereMonth('date', $month->month);
    }

    public function scopeForCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Helper methods
    public function getFormattedAmountAttribute()
    {
        return 'KSh ' . number_format($this->amount, 2);
    }

    public function getIsPendingAttribute()
    {
        return $this->status === 'pending';
    }

    public function getIsApprovedAttribute()
    {
        return $this->status === 'approved';
    }
}