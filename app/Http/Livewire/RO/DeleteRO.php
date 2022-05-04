<?php

namespace App\Http\Livewire\RO;

use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\RedirectsActions;
use Livewire\Component;

class DeleteRO extends Component
{
    use RedirectsActions;

    public $ro;
    public $confirmingRODeletion = false;

    public function mount($ro = null)
    {
        $this->ro = $ro;
    }

    public function delete()
    {
        // $validator->validate(Auth::user(), $this->team);

        $this->ro->delete();

        return $this->redirectPath(route('ro.index'));
    }

    public function render()
    {
        return view('ro.delete');
    }
}
