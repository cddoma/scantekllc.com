<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Supported Makes') }}
        </h2>
    </x-slot>
        <div class="max-w-7xl mx-auto pt-3 pb-10 sm:px-6 lg:px-8">
            @livewire('vehicle-make-table')
        </div>
</x-app-layout>
