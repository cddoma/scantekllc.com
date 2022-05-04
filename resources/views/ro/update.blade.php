<x-jet-form-section submit="updateTeamName">
    <x-slot name="title">
        {{ __('Order Details') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Repair Order Information') }}
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="name" value="{{ __('RO #') }}" />
            <x-jet-input id="ro"
                        type="text"
                        class="mt-1 block w-full"
                        wire:model.defer="state.ro"
                        placeholder="RO #"
                        autofocus />
            <x-jet-input-error for="ro" class="mt-2" />
        </div>
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="name" value="{{ __('Technician') }}" />
            <x-jet-input id="ro"
                        type="text"
                        class="mt-1 block w-full"
                        wire:model.defer="state.ro"
                        placeholder="Repair Specialist most familiar with this vehicle" />
            <x-jet-input-error for="ro" class="mt-2" />
        </div>

    </x-slot>

    <x-slot name="actions">
        <x-jet-action-message class="mr-3" on="saved">
            {{ __('Saved.') }}
        </x-jet-action-message>

        <x-jet-button>
            {{ __('Save') }}
        </x-jet-button>
    </x-slot>
</x-jet-form-section>
