<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BreedingRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'animal_id', 'date_of_service', 'breeding_method', 'bull_semen_id',
        'technician', 'pregnancy_diagnosis_date', 'pregnancy_result',
        'expected_calving_date', 'actual_calving_date', 'calves_born', 
        'calves_alive', 'calving_outcome', 'notes'
    ];

    protected $casts = [
        'date_of_service' => 'date',
        'pregnancy_diagnosis_date' => 'date',
        'expected_calving_date' => 'date',
        'actual_calving_date' => 'date',
        'pregnancy_result' => 'boolean'
    ];

    // Relationship with animal (the dam/mother)
    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }

    // Alias for animal (for readability)
    public function dam()
    {
        return $this->belongsTo(Animal::class, 'animal_id');
    }

    // Relationship with calves (if you want to link breeding records to calves)
    public function calves()
    {
        return $this->hasMany(Calf::class);
    }

    // Scopes
    public function scopePregnant($query)
    {
        return $query->where('pregnancy_result', true)
                    ->whereNull('actual_calving_date');
    }

    public function scopeCompleted($query)
    {
        return $query->whereNotNull('actual_calving_date');
    }

    public function scopeDueForCalving($query)
    {
        return $query->where('pregnancy_result', true)
                    ->whereNull('actual_calving_date')
                    ->where('expected_calving_date', '<=', now()->addDays(30));
    }

    // Helper method to calculate calves alive based on birth type
    public function calculateCalvesBorn($birthType)
    {
        return match($birthType) {
            'single' => 1,
            'twin' => 2,
            'triplet' => 3,
            default => 1,
        };
    }
}