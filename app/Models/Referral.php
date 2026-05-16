<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'referring_doctor_id', 'referred_doctor_id', 'reason', 'status'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function referringDoctor()
    {
        return $this->belongsTo(Doctor::class, 'referring_doctor_id');
    }

    public function referredDoctor()
    {
        return $this->belongsTo(Doctor::class, 'referred_doctor_id');
    }
}
