<x-powergrid-layout>
    <x-slot name="title">{{ __('Repair Orders') }}</x-slot>
    <x-slot name="header" class="inline-block">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight inline">
            {{ __('Repair Orders') }}
        </h2>
        
        <a href="{{ route('ro.create') }}"  class="inline float-right">
            <x-jet-button type="button">
                {{ __('New Repair Order') }}
            </x-jet-button>
        </a>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <livewire:r-o-table/>
        </div>
    </div>
</x-powergrid-layout>
