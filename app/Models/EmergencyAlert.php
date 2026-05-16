<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmergencyAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'latitude',
        'longitude',
        'status', // pending, responding, resolved
        'responded_at',
        'resolved_at',
        'responder_id', // branch or admin id
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function responder()
    {
        return $this->belongsTo(User::class, 'responder_id');
    }
}
