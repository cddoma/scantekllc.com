<x-jet-action-section>
    <x-slot name="title">
        {{ __('Delete Repair Order') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Permanently delete this Repair Order.') }}
    </x-slot>

    <x-slot name="content">
        <div class="mt-5">
            <x-jet-danger-button wire:click="$toggle('confirmingRODeletion')" wire:loading.attr="disabled">
                {{ __('Delete RO') }}
            </x-jet-danger-button>
        </div>

        <x-jet-confirmation-modal wire:model="confirmingRODeletion">
            <x-slot name="title">
                {{ __('Delete RO') }}
            </x-slot>

            <x-slot name="content">
                {{ __('Are you sure you want to delete this Repair Order?') }}
            </x-slot>

            <x-slot name="footer">
                <x-jet-secondary-button wire:click="$toggle('confirmingRODeletion')" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-jet-secondary-button>

                <x-jet-danger-button class="ml-3" wire:click="delete" wire:loading.attr="disabled">
                    {{ __('Delete RO') }}
                </x-jet-danger-button>
            </x-slot>
        </x-jet-confirmation-modal>
    </x-slot>
</x-jet-action-section>
