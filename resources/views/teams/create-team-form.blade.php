<x-jet-form-section submit="createTeam">
    <x-slot name="title">
        {{ __('Account Details') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Create a new account.') }}
    </x-slot>

    <x-slot name="form">
        <!-- <div class="col-span-6">
            <x-jet-label value="{{ __('Team Owner') }}" />

            <div class="flex items-center mt-2">
                <img class="w-12 h-12 rounded-full object-cover" src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}">

                <div class="ml-4 leading-tight">
                    <div>{{ $this->user->name }}</div>
                    <div class="text-gray-700 text-sm">{{ $this->user->email }}</div>
                </div>
            </div>
        </div> -->

        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="name" value="{{ __('Shop') }}" />
            <x-jet-input id="name" name="name" type="text" class="mt-1 block w-full" wire:model.defer="state.name" autofocus />
            <x-jet-input-error for="name" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="manager" value="{{ __('Manager') }}" />
            <x-jet-input id="manager" name="manager" type="text" class="mt-1 block w-full" wire:model.defer="state.manager"  />
            <x-jet-input-error for="manager" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="phone" value="{{ __('Phone') }}" />
            <x-jet-input id="phone" name="phone" type="tel" class="mt-1 block w-full" wire:model.defer="state.phone"  />
            <x-jet-input-error for="phone" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="email" value="{{ __('Email') }}" />
            <x-jet-input id="email" name="email" type="email" class="mt-1 block w-full" wire:model.defer="state.email"  />
            <x-jet-input-error for="email" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="address" value="{{ __('Address') }}" />
            <textarea rows="4" id="address" name="address" class="mt-1 block w-full" wire:model.defer="state.address"></textarea>
            <x-jet-input-error for="address" class="mt-2" />
        </div>
        <!-- <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="owner" value="{{ __('Account Owner Name') }}" />
            <x-jet-input id="owner" type="text" class="mt-1 block w-full" wire:model.defer="state.owner"  />
            <x-jet-input-error for="owner" class="mt-2" />
        </div>
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="email" value="{{ __('Account Owner Email') }}" />
            <x-jet-input id="email" type="text" class="mt-1 block w-full" wire:model.defer="state.email"  />
            <x-jet-input-error for="email" class="mt-2" />
        </div> -->
    </x-slot>

    <x-slot name="actions">
        <x-jet-button>
            {{ __('Create') }}
        </x-jet-button>
    </x-slot>
</x-jet-form-section>
