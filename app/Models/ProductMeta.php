<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\Product;

class ProductMeta extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    
    public $timestamps = false;

    protected $fillable = [
        'product_id', 
        'key', 
        'value', 
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
