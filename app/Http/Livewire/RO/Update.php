<?php

namespace App\Http\Livewire\RO;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\RedirectsActions;

class Update extends Component
{
    use RedirectsActions;

    public $ro;
    public $confirmingRODeletion = false;

    public function mount($ro = null)
    {
        $this->ro = $ro;
    }

    public function render()
    {
        return view('ro.update');
    }
}
