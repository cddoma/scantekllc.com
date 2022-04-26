<x-jet-form-section submit="updateTeamName">
    <x-slot name="title">
        {{ __('Account Name') }}
    </x-slot>

    <x-slot name="description">
        {{ __('The account\'s name and owner information.') }}
    </x-slot>

    <x-slot name="form">
        <!-- Team Name -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="name" value="{{ __('Account Name') }}" />

            <x-jet-input id="name"
                        type="text"
                        class="mt-1 block w-full"
                        wire:model.defer="state.name"
                        :disabled="! Gate::check('update', $team)" />

            <x-jet-input-error for="name" class="mt-2" />
        </div>

        <!-- Team Owner -->
        <!-- <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="owner" value="{{ __('Account Owner') }}" />

            <x-jet-input
                        type="text"
                        class="mt-1 block w-full"
                        value="{{ $team->owner->name }}"
                        disabled="disabled" />
            <x-jet-input
                        type="text"
                        class="mt-1 block w-full"
                        value="{{ $team->owner->email }}"
                        disabled="disabled" />
        </div> -->
    </x-slot>

    @if (Gate::check('update', $team))
        <x-slot name="actions">
            <x-jet-action-message class="mr-3" on="saved">
                {{ __('Saved.') }}
            </x-jet-action-message>

            <x-jet-button>
                {{ __('Save') }}
            </x-jet-button>
        </x-slot>
    @endif
</x-jet-form-section>
