<?php
// app/Models/Animal.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Animal extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'animal_id', 'name', 'ear_tag', 'breed', 'date_of_birth', 'sex',
        'dam_id', 'sire_id', 'source', 'status', 'date_added', 'notes', 'is_active'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'date_added' => 'date',
        'is_active' => 'boolean'
    ];

    // Relationships
    public function breedingRecords()
    {
        return $this->hasMany(BreedingRecord::class);
    }

    public function milkProductions()
    {
        return $this->hasMany(MilkProduction::class);
    }

    public function healthRecords()
    {
        return $this->hasMany(HealthRecord::class);
    }

    public function dam()
    {
        return $this->belongsTo(Animal::class, 'dam_id');
    }

    public function sire()
    {
        return $this->belongsTo(Animal::class, 'sire_id');
    }

    public function offspring()
    {
        return $this->hasMany(Animal::class, 'dam_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeLactating($query)
    {
        return $query->where('status', 'lactating');
    }

    public function scopePregnant($query)
    {
        return $query->whereHas('breedingRecords', function($q) {
            $q->where('pregnancy_result', true)
              ->where('actual_calving_date', null);
        });
    }
}