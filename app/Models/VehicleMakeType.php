<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleMakeType extends Model
{
    use HasFactory;
    protected $table = 'vehicle_make_types';
    protected $fillable = [
        'name', 'vpic_id', 'vpic_make_id'
    ];
    public function make()
    {
        return $this->belongsTo(VehicleMake::class, 'vpic_make_id', 'vpic_id');
    }
}
