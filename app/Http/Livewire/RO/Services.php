<?php

namespace App\Http\Livewire\RO;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\RedirectsActions;

class Services extends Component
{
    use RedirectsActions;

    public $ro;

    public function mount($ro = null)
    {
        $this->ro = $ro;
    }

    public function render()
    {
        return view('ro.services');
    }
}
