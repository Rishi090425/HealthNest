<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalkInQueue extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'department_id',
        'queue_number',
        'status',
        'estimated_wait_time',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
