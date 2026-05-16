<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'medication_id',
        'current_stock',
        'reorder_level',
        'branch_id',
    ];

    public function medication()
    {
        return $this->belongsTo(Medication::class);
    }
}
