<x-jet-form-section onsubmit="return false;" submit="save" wire:submit.defer="save">
    <x-slot name="title">
        {{ __('VIN Details') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Metadata pulled from the vehicle\'s VIN') }}
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6">
        @livewire('vehicle-meta-table', ['vehicleId' => $vehicleId])
        </div>
    </x-slot>

    <x-slot name="actions">
    
    </x-slot>
</x-jet-form-section>
