<?php

namespace App\Http\Livewire\RO;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Models\Team;
use App\Models\User;

class AdjusterList extends Component
{
    public $adjusters;
    public $team_id;

    protected $listeners = ['updateAdjusterOptions' => 'update'];

    public function update($team_id)
    {
        $this->adjusters = User::where('current_team_id', $team_id)->select('name', 'id')->get()->toArray();
    }

    public function mount($team_id)
    {
        $this->team_id = $team_id;
    }

    public function render()
    {
        if(empty($this->adjusters)) {
            if(empty($this->team_id)) {
                $this->team_id = \Auth::user()->current_team_id;
            }
            $this->update($this->team_id);
        }
        return view('livewire.r-o.adjuster-list');
    }
}
