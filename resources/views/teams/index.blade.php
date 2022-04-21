<x-powergrid-layout>
    <x-slot name="title">{{ __('Accounts') }}</x-slot>
    <x-slot name="header" class="inline-block">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight inline">
            {{ __('Accounts') }}
        </h2>
        
        <a href="{{ route('accounts.create') }}"  class="inline float-right">
            <x-jet-button type="button">
                {{ __('New Account') }}
            </x-jet-button>
        </a>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <livewire:team-table/>
        </div>
    </div>
</x-powergrid-layout>
