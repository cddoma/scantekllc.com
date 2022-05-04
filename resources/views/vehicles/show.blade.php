<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create RO') }}
        </h2>
    </x-slot>
        <div class="max-w-7xl mx-auto pt-3 pb-10 sm:px-6 lg:px-8">
            @if(isset($vehicleId))
                @livewire('vehicles.update', [$vehicleId])
                <div class="mx-auto mt-6">
                    @livewire('vehicle-meta-table', [$vehicleId])
                </div>
            @else
                @livewire('vehicles.update', [0])
            @endif

        </div>
</x-app-layout>
