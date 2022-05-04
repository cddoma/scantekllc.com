<x-powergrid-layout>
    <x-slot name="title">{{ __('Repair Orders') }}</x-slot>

    <div>
        <a href="{{ route('vehicles.create') }}"  class="inline float-right">
            <x-jet-button type="button">
                {{ __('New Repair Order') }}
            </x-jet-button>
        </a>
        <div class="max-w-7xl mx-auto pt-1 pb-10 sm:px-6 lg:px-8">
            <livewire:r-o-table/>
        </div>
    </div>
</x-powergrid-layout>
