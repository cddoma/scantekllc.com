<x-jet-form-section submit="updateTeamName">
    <x-slot name="title">
        {{ __('Shop Name') }}
    </x-slot>

    <x-slot name="description">
        {{ __('The account\'s information.') }}
    </x-slot>

    <x-slot name="form">
        <!-- Team Name -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="company" value="{{ __('Shop Name') }}" />
            <x-jet-input id="company"
                        type="text"
                        class="mt-1 block w-full"
                        wire:model.defer="state.name"
                        :disabled="! Gate::check('update', $team)" />

            <x-jet-input-error for="company" class="mt-2" />
        </div>
        <!-- Manager Name -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="name" value="{{ __('Manager') }}" />
            <x-jet-input id="name"
                        type="text"
                        class="mt-1 block w-full"
                        wire:model.defer="state.manager"
                        :disabled="! Gate::check('update', $team)" />

            <x-jet-input-error for="name" class="mt-2" />
        </div>
        <!-- Phone -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="phone" value="{{ __('Phone') }}" />
            <x-jet-input id="phone"
                        type="text"
                        class="mt-1 block w-full"
                        wire:model.defer="state.phone"
                        :disabled="! Gate::check('update', $team)" />

            <x-jet-input-error for="phone" class="mt-2" />
        </div>
        <!-- Email -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="email" value="{{ __('Email') }}" />
            <x-jet-input id="email"
                        type="text"
                        class="mt-1 block w-full"
                        wire:model.defer="state.email"
                        :disabled="! Gate::check('update', $team)" />

            <x-jet-input-error for="email" class="mt-2" />
        </div>
        <!-- Address -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="address" value="{{ __('Address') }}" />
            <textarea rows="4" id="address" class="mt-1 block w-full" wire:model.defer="state.address"></textarea>
            <x-jet-input-error for="address" class="mt-2" />
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
