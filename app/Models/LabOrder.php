<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'doctor_id', 'order_date', 'test_type', 'status', 'notes'
    ];

    protected $casts = ['order_date' => 'date'];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function results()
    {
        return $this->hasMany(LabResult::class);
    }
}
