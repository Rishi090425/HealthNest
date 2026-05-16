<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'subject',
        'message',
        'type',
        'status',
        'admin_response',
        'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending'   => 'bg-yellow-100 text-yellow-800',
            'in_review' => 'bg-blue-100 text-blue-800',
            'resolved'  => 'bg-green-100 text-green-800',
            default     => 'bg-gray-100 text-gray-800',
        };
    }

    public function getTypeLabelAttribute()
    {
        return match($this->type) {
            'complaint'      => '⚠️ Complaint',
            'change_request' => '✏️ Change Request',
            'feedback'       => '💬 Feedback',
            default          => $this->type,
        };
    }
}
