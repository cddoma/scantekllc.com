<x-jet-form-section submit="updateVehicle">
    <x-slot name="title">
        <div class="mt-6">
        {{ __('Vehicle Information') }}
        </div>
    </x-slot>
    <x-slot name="description">
    </x-slot>
    <x-slot name="form">
<style>
    input[type="search"]::-webkit-search-cancel-button {

    /* Remove default */
    -webkit-appearance: none;

    /* Now your own custom styles */
    height: 18px;
    width: 18px;
    display: inline-block;
    background: transparent;
    background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAAn0lEQVR42u3UMQrDMBBEUZ9WfQqDmm22EaTyjRMHAlM5K+Y7lb0wnUZPIKHlnutOa+25Z4D++MRBX98MD1V/trSppLKHqj9TTBWKcoUqffbUcbBBEhTjBOV4ja4l4OIAZThEOV6jHO8ARXD+gPPvKMABinGOrnu6gTNUawrcQKNCAQ7QeTxORzle3+sDfjJpPCqhJh7GixZq4rHcc9l5A9qZ+WeBhgEuAAAAAElFTkSuQmCC);
    /* setup all the background tweaks for our custom icon */
    background-repeat: no-repeat;
    margin-right: 10px;
    /* icon size */
    background-size: 18px;
    }
    input::placeholder { /* Chrome, Firefox, Opera, Safari 10.1+ */
        opacity: .5;
    }
</style>
        <!-- Team Name -->
@php 
    $autofocus = !empty($this->state['id']) ? ' autofocus ' : '';
@endphp
    @if(\Auth::user()->super_admin)
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label  style="cursor:pointer;" for="team_id" value="{{ __('Shop') }}" />
            <select id="team_id"
                        name="team_id"
                        class="mt-1 block w-full"
                        wire:model="state.team_id"
            >
                <option selected value disabled></option>
                @foreach($teams as $team)
                    <option value="{{ $team['id'] }}" >{{ $team['name'] }}</option>
                @endforeach
            </select>
            <a title="Go to Account" style="cursor:pointer;" href="{{ route('accounts.show', 1) }}"></a>
        </div>
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="name" value="{{ __('RO#') }}" />
            <x-jet-input id="ro"
                        type="text"
                        class="mt-1 block w-full"
                        wire:model.defer="ro.ro"
                        placeholder="RO#"
                        autofocus />
            <x-jet-input-error for="ro" class="mt-2" />
        </div>
    @endif
        <div class="col-span-6 sm:col-span-4 border border-2 p-2" style="border-color: #a9a9a9">
            <div class="col-span-6 sm:col-span-4 mb-1">
                <x-jet-label for="name" value="{{ __('YEAR MAKE MODEL') }}" />
                <x-jet-input id="name"
                            name="name"
                            type="search"
                            list="vehicleOptions"
                            class="mt-1 block w-full"
                            wire:model="state.name"
                            placeholder="YEAR MAKE MODEL"
                            autocomplete="off"
                />
                <livewire:vehicles.select-list/>
                <x-jet-input-error for="name" class="mt-2" />
                <div id="vehicleIds" class="hidden">
                    <x-jet-input id="vehicleyear" name="year" type="hidden" wire:model.defer="state.year"/>
                    <x-jet-input id="vehiclemake" name="vpic_make_id" type="hidden"  wire:model.defer="state.vpic_make_id"/>
                    <x-jet-input id="vehiclemodel" name="vpic_model_id" type="hidden" wire:model.defer="state.vpic_model_id"/>
                </div>
            </div>
            <div class="text-center" style="margin-bottom:-1rem;font-weight: bold; color: #999">OR</div>
            <div class="col-span-6 sm:col-span-4" >
                <x-jet-label for="vin" value="{{ __('VIN') }}" />
                <x-jet-input id="vin"
                            name="vin"
                            type="text"
                            class="mt-1 block w-full"
                            wire:model.defer="state.vin"
                            placeholder="VIN"
                            pattern="[0-9,a-z,A-Z]{17}" 
                            maxlength=17
                            autocomplete="off"
                            />
                <x-jet-input-error for="vin" class="mt-2" />
            </div>
        </div>
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="product" value="{{ __('Service') }}" />
            <x-jet-input id="product"
                        name="product"
                        placeholder="Service"
                        type="text"
                        list="productOptions"
                        class="mt-1 block w-full"
                        wire:model.defer="product"
            />

            <datalist id="productOptions">
                @if(!empty($products))
                    @foreach($products as $product)
                        <option wire:click="updateProduct({{ $product['id'] }})" value="{{ $product['name'] }}"/>
                    @endforeach
                @endif
            </datalist>
        </div>
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="technician" value="{{ __('Technician') }}" />
            <x-jet-input id="technician"
                        type="text"
                        class="mt-1 block w-full"
                        wire:model.defer="ro.technician"
                        placeholder="Technician" />
            <x-jet-input-error for="technician" class="mt-2" />
        </div>
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="adjuster" value="{{ __('Adjuster') }}" />
            <x-jet-input id="adjuster"
                        name="adjuster"
                        placeholder="Adjuster"
                        type="text"
                        list="adjusterOptions"
                        class="mt-1 block w-full"
            />
            @if(!empty($this->state['team_id']))
                @livewire('r-o.adjuster-list', [
                    'team_id' => $this->state['team_id']
                ])
            @endif
        </div>

        <!-- <div class="col-span-6 sm:col-span-4">
            <label class="pt-1 mt-2 bg-gray-800 p-2 border inline-block text-white text-center" for="color" title="{{ __('Vehicle Color') }}">
                {{ __('Vehicle Color') }}
                <input id="color" name="color" wire:model.defer="state.color" type="color" class="mx-auto text-center inline-block">
            </label>
        </div> -->
        

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