<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\Product;
use App\Models\RepairOrder;

class RepairOrderProduct extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'product_id', 
        'repair_order_id', 
        'price', 
        'name', 
        'notes', 
    ];

    protected $casts = [
        'product_id' => 'integer',
        'repair_order_id' => 'integer',
        'price' => 'decimal:8,2',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function repairOrder()
    {
        return $this->belongsTo(RepairOrder::class);
    }
}
