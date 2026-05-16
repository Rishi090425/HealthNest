<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vital extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'recorded_at',
        'blood_pressure_systolic', 'blood_pressure_diastolic',
        'heart_rate', 'temperature', 'respiratory_rate',
        'weight', 'height', 'oxygen_saturation', 'blood_glucose'
    ];

    protected $casts = ['recorded_at' => 'datetime'];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    // Calculated BMI
    public function getBmiAttribute()
    {
        if ($this->weight && $this->height && $this->height > 0) {
            $heightM = $this->height / 100;
            return round($this->weight / ($heightM * $heightM), 1);
        }
        return null;
    }
}
