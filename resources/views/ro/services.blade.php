<x-jet-form-section submit="updateTeamName">
    <x-slot name="title">
        {{ __('Service Details') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Requested calibrations or other services.') }}
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="service" value="{{ __('Requested Services') }}" />
            <x-jet-input id="service"
                        type="text"
                        class="mt-1 block w-full"
                        wire:model.defer="state.service"
                        placeholder="Select a Service"
                        autofocus />
            <x-jet-input-error for="service" class="mt-2" />
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
