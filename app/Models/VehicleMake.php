<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleMake extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'vpic_id'
    ];

    protected $casts = [
        'vpic_id' => 'int',
    ];
}
