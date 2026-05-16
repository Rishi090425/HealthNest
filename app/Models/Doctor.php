<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'specialization', 'specialty_id', 'department_id',
        'qualification', 'experience_years', 'license_number',
        'bio', 'avatar', 'consultation_fee', 'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function availabilities()
    {
        return $this->hasMany(DoctorAvailability::class);
    }

    public function consultations()
    {
        return $this->hasManyThrough(Consultation::class, Appointment::class);
    }

    public function soapNotes()
    {
        return $this->hasMany(SoapNote::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    public function labOrders()
    {
        return $this->hasMany(LabOrder::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function referralsSent()
    {
        return $this->hasMany(Referral::class, 'referring_doctor_id');
    }

    public function referralsReceived()
    {
        return $this->hasMany(Referral::class, 'referred_doctor_id');
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }

    public function getFullNameAttribute()
    {
        return $this->user->name ?? '';
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating');
    }
}
