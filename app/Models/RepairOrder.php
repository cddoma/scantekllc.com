<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\Team;
use App\Models\RepairOrderProduct;
use App\Models\Product;
use App\Models\Vehicle;

class RepairOrder extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'team_id', 
        'created_by', 
        'vehicle_id', 
        'priority', 
        'status', 
        'service_advisor', 
        'technician', 
        'user_notes', 
        'notes', 
    ];

    protected $casts = [
        'team_id' => 'integer',
        'created_by' => 'integer',
        'vehicle_id' => 'integer',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function allProducts()
    {
        return $this->hasManyThrough(Product::class, RepairOrderProduct::class);
    }

    public function vehicle()
    {
        return $this->hasOne(Vehicle::class, 'id', 'vehicle_id');
    }

    public function createdBy()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public static function getByRO($ro) {
        $user = \Auth::user();
        $filters = [
            ['ro', $ro],
        ];
        if(!$user->super_admin) {
            $filters[] = ['team_id', $user->current_team_id];
        }
        $ro_entity = RepairOrder::where($filters)->first();
        if(empty($ro_entity)) {
            $ro_entity = RepairOrder::findOrFail(intval($ro));
        }
        return $ro_entity;
    }
}
