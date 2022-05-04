<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\Vehicle;

class VehicleMeta extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    
    public $timestamps = false;

    protected $fillable = [
        'vehicle_id', 
        'key', 
        'value', 
        'source', 
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
