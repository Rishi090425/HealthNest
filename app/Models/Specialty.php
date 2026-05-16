<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specialty extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'department_id', 'description'];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }
}
