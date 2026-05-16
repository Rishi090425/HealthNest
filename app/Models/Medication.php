<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medication extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'generic_name',
        'category',
        'unit', // tablet, bottle, ml, etc.
        'unit_price',
    ];

    public function inventory()
    {
        return $this->hasOne(InventoryLevel::class);
    }
}
