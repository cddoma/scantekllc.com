<?php

namespace App\Http\Livewire\RO;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\RedirectsActions;

class Vindata extends Component
{
    use RedirectsActions;

    public $vehicleId;

    public function mount($vehicleId)
    {
        $this->vehicleId = $vehicleId;
    }

    public function render()
    {
        return view('ro.vindata', ['vehicleId' => $this->vehicleId]);
    }

    public function save() {
        $this->skipRender();
        return false;
    }
}
