<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleMake extends Model
{
    use HasFactory;
    // protected $table = 'vehicle_make_types';

    protected $fillable = [
        'name', 'vpic_id'
    ];

    protected $casts = [
        'vpic_id' => 'int',
    ];

    public function type()
    {
        return $this->hasOne(VehicleMakeType::class, 'vpic_make_id', 'vpic_id');
    }
}
