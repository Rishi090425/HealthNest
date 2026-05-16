<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'date_of_birth', 'gender', 'blood_group',
        'address', 'medical_history', 'emergency_contact_name',
        'emergency_contact_phone', 'avatar',
    ];

    protected $casts = ['date_of_birth' => 'date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    public function labOrders()
    {
        return $this->hasMany(LabOrder::class);
    }

    public function vitals()
    {
        return $this->hasMany(Vital::class)->orderByDesc('recorded_at');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function soapNotes()
    {
        return $this->hasMany(SoapNote::class);
    }

    public function referrals()
    {
        return $this->hasMany(Referral::class);
    }

    public function getAgeAttribute()
    {
        return $this->date_of_birth ? Carbon::parse($this->date_of_birth)->age : null;
    }

    public function getFullNameAttribute()
    {
        return $this->user->name ?? '';
    }
}
