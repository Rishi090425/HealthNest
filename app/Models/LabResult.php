<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'lab_order_id', 'result_date', 'result_value', 'normal_range', 'flag', 'report_path'
    ];

    protected $casts = ['result_date' => 'date'];

    public function labOrder()
    {
        return $this->belongsTo(LabOrder::class);
    }
}
