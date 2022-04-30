<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\ProductMeta;

class Product extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'stripe_id', 
        'name', 
        'description', 
        'notes', 
        'hidden', 
        'active', 
        'default_price', 
    ];

    protected $casts = [
        'default_price' => 'decimal:8,2',
    ];

    public function metas()
    {
        return $this->hasMany(ProductMeta::class);
    }
}
