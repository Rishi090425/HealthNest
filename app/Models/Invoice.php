<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'appointment_id', 'amount', 'status', 'due_date', 'issued_date'
    ];

    protected $casts = [
        'due_date' => 'date',
        'issued_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getTotalPaidAttribute()
    {
        return $this->payments->sum('amount');
    }

    public function getBalanceAttribute()
    {
        return $this->amount - $this->total_paid;
    }
}
