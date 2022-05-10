<x-powergrid-layout>
    <x-slot name="title">{{ __('Repair Orders') }}</x-slot>

    <div>
        <a href="{{ route('vehicles.create') }}"  class="inline float-right ml-1 mt-1 mr-3" style="line-height: 2.125em;">
            <x-jet-button type="button">
                {{ __('New RO') }}
            </x-jet-button>
        </a>
        <div class="max-w-7xl mx-auto pt-1 pb-10 sm:px-6 lg:px-8">
            <livewire:r-o-table/>
        </div>
    </div>
</x-powergrid-layout>
