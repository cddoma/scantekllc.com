<?php

namespace App\Http\Livewire\RO;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Models\Team;
use App\Models\User;

class ProductList extends Component
{
    public $products;
    public $team_id;

    public function render()
    {
        if(empty($this->products)) {
            if(empty($this->team_id)) {
                $this->team_id = \Auth::user()->current_team_id;
            }
            $this->update($this->team_id);
        }
        return view('livewire.r-o.adjuster-list');
    }
}