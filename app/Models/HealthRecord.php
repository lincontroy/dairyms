<?php
// app/Models/HealthRecord.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'animal_id', 'date', 'diagnosis', 'clinical_signs', 'treatment',
        'drug_name', 'dosage', 'route', 'duration', 'milk_withdrawal_days',
        'meat_withdrawal_days', 'veterinarian', 'outcome', 'notes'
    ];

    protected $casts = [
        'date' => 'date'
    ];

    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }
}