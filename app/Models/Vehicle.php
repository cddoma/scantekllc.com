<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Vehicle extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'name', 'vin', 'year', 'make', 'model', 'trim', 'team_id', 'vpic_model_id', 'vpic_make_id'
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
