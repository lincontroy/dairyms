<?php
// app/Models/BreedingRecord.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BreedingRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'animal_id', 'date_of_service', 'breeding_method', 'bull_semen_id',
        'technician', 'pregnancy_diagnosis_date', 'pregnancy_result',
        'expected_calving_date', 'actual_calving_date', 'calving_outcome', 'notes'
    ];

    protected $casts = [
        'date_of_service' => 'date',
        'pregnancy_diagnosis_date' => 'date',
        'expected_calving_date' => 'date',
        'actual_calving_date' => 'date',
        'pregnancy_result' => 'boolean'
    ];

    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }
}