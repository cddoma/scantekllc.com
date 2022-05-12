<?php

namespace App\Http\Livewire\RO;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Models\Team;
use App\Models\User;
use App\Models\RepairOrder;

class TechnicianList extends Component
{
    public $technicians;
    public $team_id;

    protected $listeners = ['updateAdjusterOptions' => 'update'];

    public function update($team_id)
    {
        $this->technicians = RepairOrder::where([['team_id', $team_id],['technician', '!=', '']])->groupBy('technician')->get()->pluck('technician')->toArray();
    }

    public function mount($team_id)
    {
        $this->team_id = $team_id;
    }

    public function render()
    {
        if(empty($this->technicians)) {
            if(empty($this->team_id)) {
                $this->team_id = \Auth::user()->current_team_id;
            }
            $this->update($this->team_id);
        }
        return view('livewire.r-o.technician-list');
    }
}
