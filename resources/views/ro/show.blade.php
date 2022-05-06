<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Repair Order') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            {{-- @livewire('r-o.services', ['ro_id' => $ro_id]) --}}
            {{-- <x-jet-section-border /> --}}
            {{-- @livewire('r-o.update', ['ro_id' => $ro_id]) --}}
            {{-- <x-jet-section-border /> --}}
            @livewire('vehicles.update', ['ro_id' => $ro_id])
            <x-jet-section-border />
            @livewire('r-o.vindata', ['vehicleId' => $vehicleId])
            <x-jet-section-border />
            @livewire('r-o.delete-r-o')

            @if (\Auth::user()->super_admin)
            @endif
        </div>
    </div>
</x-app-layout>

