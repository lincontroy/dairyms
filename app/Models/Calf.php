<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Calf extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'calf_id', 'dam_id', 'sire_id', 'breeding_record_id', 'name', 'ear_tag',
        'sex', 'date_of_birth', 'date_recorded', 'birth_weight', 'birth_type',
        'delivery_type', 'health_status', 'status', 'notes', 'color_markings',
        'vaccination_status', 'weaning_date', 'weaning_weight', 'is_weaned',
        'requires_special_care', 'special_care_notes', 'recorded_by'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'date_recorded' => 'date',
        'weaning_date' => 'date',
        'birth_weight' => 'decimal:2',
        'weaning_weight' => 'decimal:2',
        'is_weaned' => 'boolean',
        'requires_special_care' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($calf) {
            if (empty($calf->calf_id)) {
                $calf->calf_id = static::generateCalfId();
            }
        });
    }

    public static function generateCalfId()
    {
        $year = date('Y');
        $month = date('m');
        
        $lastCalf = static::where('calf_id', 'like', "CALF-{$year}{$month}%")
            ->orderBy('calf_id', 'desc')
            ->first();
        
        if ($lastCalf) {
            $lastNumber = intval(substr($lastCalf->calf_id, -3));
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }
        
        return "CALF-{$year}{$month}{$newNumber}";
    }

    // Relationships
    public function dam()
    {
        return $this->belongsTo(Animal::class, 'dam_id');
    }

    public function sire()
    {
        return $this->belongsTo(Animal::class, 'sire_id');
    }

    public function breedingRecord()
    {
        return $this->belongsTo(BreedingRecord::class);
    }
    

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function healthRecords()
    {
        return $this->hasMany(CalfHealthRecord::class);
    }

    public function feedingRecords()
    {
        return $this->hasMany(CalfFeedingRecord::class);
    }

    public function weightRecords()
    {
        return $this->hasMany(CalfWeightRecord::class);
    }

    // Scopes
    public function scopeAlive($query)
    {
        return $query->where('status', 'alive');
    }

    public function scopeDead($query)
    {
        return $query->where('status', 'dead');
    }

    public function scopeSold($query)
    {
        return $query->where('status', 'sold');
    }

    public function scopeWeaned($query)
    {
        return $query->where('is_weaned', true);
    }

    public function scopeNeedingWeaning($query)
    {
        return $query->where('is_weaned', false)
            ->whereNotNull('weaning_date')
            ->where('weaning_date', '<=', now());
    }

    public function scopeRequiringSpecialCare($query)
    {
        return $query->where('requires_special_care', true);
    }

    public function scopeBornThisMonth($query)
    {
        return $query->whereMonth('date_of_birth', now()->month)
            ->whereYear('date_of_birth', now()->year);
    }

    public function scopeBornLast30Days($query)
    {
        return $query->where('date_of_birth', '>=', now()->subDays(30));
    }

    // Helper Methods
    public function getAgeInDaysAttribute()
    {
        return $this->date_of_birth->diffInDays(now());
    }

    public function getAgeInMonthsAttribute()
    {
        return $this->date_of_birth->diffInMonths(now());
    }

    public function isVaccinationComplete()
    {
        return $this->vaccination_status === 'complete';
    }

    public function markAsWeaned($weight = null)
    {
        $this->update([
            'is_weaned' => true,
            'weaning_date' => now(),
            'weaning_weight' => $weight ?? $this->weaning_weight
        ]);
    }

    public function updateHealthStatus($status)
    {
        $this->update(['health_status' => $status]);
    }

    public function recordDeath()
    {
        $this->update(['status' => 'dead']);
    }

    public function recordSale()
    {
        $this->update(['status' => 'sold']);
    }
}