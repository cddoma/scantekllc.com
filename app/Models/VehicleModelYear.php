<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleModelYear extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'year', 'vpic_make_id', 'vpic_model_id'
    ];

    protected $casts = [
        'vpic_model_id' => 'int',
        'vpic_make_id' => 'int',
    ];
}
