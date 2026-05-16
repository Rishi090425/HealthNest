<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'dark_mode'];

    protected $casts = [
        'dark_mode' => 'boolean',
    ];
}
