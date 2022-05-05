<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleModel extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function make()
    {
        return $this->hasOne(VehicleMake::class, 'vpic_id', 'vpic_make_id');
    }
}
