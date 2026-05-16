<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vaccination extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'vaccine_name',
        'date_received',
        'provider',
        'next_due_date',
        'notes',
    ];

    protected $casts = [
        'date_received' => 'date',
        'next_due_date' => 'date',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
