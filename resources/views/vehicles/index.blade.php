<x-powergrid-layout>
    <x-slot name="title">{{ __('Accounts') }}</x-slot>

    <div>
        <a href="{{ route('accounts.create') }}"  class="inline float-right">
            <x-jet-button type="button">
                {{ __('New Shop') }}
            </x-jet-button>
        </a>
        <div class="max-w-7xl mx-auto pt-1 pb-10 sm:px-6 lg:px-8">
            <livewire:team-table/>
        </div>
    </div>
</x-powergrid-layout>
