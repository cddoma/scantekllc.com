<?php

namespace App\Http\Livewire\RO;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Models\Team;
use App\Models\RepairOrderProduct;
use App\Models\User;

class ProductList extends Component
{
    public $products;
    public $ro_id;
    protected $listeners = ['updateAdjusterOptions' => 'update'];

    public function mount($ro_id)
    {
        $this->ro_id = $ro_id;
    }

    public function render()
    {
        $this->adjusters = RepairOrderProduct::where('ro_id', $this->ro_id)
            ->leftJoin('repair_order_products', 'repair_orders.id', '=', 'repair_order_products.repair_order_id')
            ->leftJoin('products', 'repair_order_products.product_id', '=', 'products.id')
            ->groupBy('technician')->get()->pluck('technician')->toArray();
        return view('livewire.r-o.adjuster-list');
    }
}