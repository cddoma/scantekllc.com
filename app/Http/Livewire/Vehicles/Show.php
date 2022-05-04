<?php

namespace App\Http\Livewire\Vehicles;

use Livewire\Component;
use App\Models\Vehicle;

class Show extends Component
{
    public $vehicle;

    public function mount($id)
    {
        if(empty($id) || $id == 'create') {
            $this->vehicle = new Vehicle();
        } else {
            $this->vehicle = Vehicle::findOrFail($id)->withoutRelations()->toArray();
        }
    }
    public function render()
    {
        return view('livewire.vehicles.show', ['vehicle' => $this->vehicle]);
    }
}
