<form  style="width:100%; margin-left:auto; margin-right:auto;" class="md:px-20" onsubmit="return false;">

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
    $autofocus = !empty($this->ro_id) ? ' autofocus ' : '';
@endphp
        <div class="inline-block text-center py-3 mx-auto w-full" style="">
            
            <div class="inline-block mx-auto ">
                @if(\Auth::user()->super_admin)
                <div class="col-span-6 sm:col-span-3 inline-block">
                    <div class="mr-2">
                        <x-jet-label  style="cursor:pointer;" for="team_id" value="{{ __('Shop') }}" />
                    </div>
                    <div class="inline-block"  style="">
                        <select id="team_id"
                                    name="team_id"
                                    class="mt-1 block"
                                    wire:model="team_id"
                                    autofocus
                                    onready="this.click();"
                        >
                            <option selected value=""></option>
                            @foreach($teams as $team)
                                <option value="{{ $team['id'] }}" >{{ $team['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @endif

                <div class="col-span-6 sm:col-span-3 inline-block">
                    <div class="mr-2">
                        <x-jet-label class="" for="ro" value="{{ __('RO') }}" />
                    </div>
                    <div class="inline-block"  style="">
                        <x-jet-input id="ro"
                                    type="text"
                                    class="mt-1"
                                    wire:model.defer="ro_num"
                                    placeholder="RO#"
                                    autocomplete="off"
                                    />
                        <x-jet-input-error for="ro" class="mt-2" />
                    </div>
                </div>
            </div>

            <div class="inline-block mt-2">
                <div class="col-span-6 sm:col-span-3 inline-block">
                <div class="mr-2">
                    <x-jet-label for="adjuster" value="{{ __('Adjuster') }}" />
                </div>
                <div class="inline-block"  style="">
                    @if(!empty($this->team_id))
                        @livewire('r-o.adjuster-list', [
                            'team_id' => $this->team_id
                        ])
                    @endif
                    <x-jet-input id="adjuster"
                                name="adjuster"
                                wire:model.defer="adjuster"
                                placeholder="Adjuster"
                                type="text"
                                list="adjusterOptions"
                                class="mt-1"
                    />
                    <x-jet-input-error for="adjuster" class="mt-2" />
                </div>
                </div>
                <div class="col-span-6 sm:col-span-3 inline-block">
                    <div class="mr-2">
                        <x-jet-label for="technician" value="{{ __('Technician') }}" />
                    </div>
                    <div class="inline-block"  >
                        @if(!empty($this->team_id))
                            @livewire('r-o.technician-list', [
                                'team_id' => $this->team_id
                            ])
                        @endif
                        <x-jet-input id="technician"
                                    type="text"
                                    class="mt-1"
                                    wire:model.defer="technician"
                                    list="techniciansOptions"
                                    placeholder="Technician" />
                        <x-jet-input-error for="technician" class="mt-2" />
                    </div>
                </div>
            </div>

            <hr class="mt-5 mb-0">

            <div class="block">
                <div class="block text-center mx-auto">
                    <div class="mx-auto mt-5 mr-3">
                        <x-jet-label class="block" for="name" value="{{ __('Vehicle') }}" />
                        <x-jet-label class="block" for="name" value="{{ __('[Year Make Model]  or  [VIN]') }}" />
                    </div>
                    <div class="w-auto">
                        <x-jet-input id="name"
                                    name="name"
                                    type="search"
                                    list="vehicleOptions"
                                    class="mt-1 w-full sm:max-w-sm"
                                    style=""
                                    title="{{ $this->search ?? '' }}"
                                    wire:model="search"
                                    placeholder="[YEAR MAKE MODEL] or [VIN]"
                        />
                        <livewire:vehicles.select-list/>
                        <x-jet-input-error for="name" class="mt-2 inline-block" />
                        <div id="vehicleIds" class="hidden inline-block">
                            <x-jet-input id="vehicleyear" name="year" type="hidden" wire:model.defer="year"/>
                            <x-jet-input id="vehiclemake" name="vpic_make_id" type="hidden"  wire:model.defer="vpic_make_id"/>
                            <x-jet-input id="vehiclemodel" name="vpic_model_id" type="hidden" wire:model.defer="vpic_model_id"/>
                        </div>
                        <!-- <div class="inline-block">
                            <x-jet-label class="block" for="name" value="{{ __('VIN') }}" />
                            <x-jet-input id="vin"
                                        name="vin"
                                        type="text"
                                        class="mt-1 inline-block"
                                        style="min-width:13em;"
                                        wire:model.defer="vin"
                                        placeholder="VIN"
                                        pattern="[0-9,a-z,A-Z]{17}" 
                                        maxlength=17
                                        autocomplete="off"
                                        />
                            <x-jet-input-error for="vin" class="mt-2" />
                        </div> -->
                    </div>
                </div>
            </div>
            <div class="block w-full mt-2">
                <div class="inline-block shadow shadow-md rounded" style="width:100%">
                    <datalist id="productOptions">
                        @if(!empty($products))
                            @foreach($products as $product)
                                <option data-value="{{ $product['id'] }}">{{ $product['name'] }}</option>
                            @endforeach
                        @endif
                    </datalist>
                    <x-jet-input id="product"
                                name="product"
                                placeholder="Service"
                                type="search"
                                list="productOptions"
                                class="mt-1 block w-full"
                                style=" max-width:34%"
                                wire:model="product"
                                wire:keydown="updateProduct(document.getElementById('productOptions').options[0].getAttribute('data-value'));"
                    />
                    @if(\Auth::user()->super_admin && 0)
                    <x-jet-input id="product_price"
                                name="product_price"
                                placeholder="Price"
                                max="4"
                                type="text"
                                class="mt-1"
                                style="max-width:6em;"
                                wire:model="product_price"
                    />
                    <button type="button" class="float-right mt-2 mr-2 sm:mr-5 items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition" wire:click="addProduct(document.getElementById('product').value, document.getElementById('product_price').value);">+</button>
                    @endif
                    @if(!empty($this->ro_id))
                        @livewire('r-o-products-table', [
                            'ro_id' => $this->ro_id
                        ])
                    @endif

                </div>
            </div>


        </div>
            

        

        <!-- <div class="col-span-6 sm:col-span-4">
            <label class="pt-1 mt-2 bg-gray-800 p-2 border inline-block text-white text-center" for="color" title="{{ __('Vehicle Color') }}">
                {{ __('Vehicle Color') }}
                <input id="color" name="color" wire:model.defer="state.color" type="color" class="mx-auto text-center inline-block">
            </label>
        </div> -->
        

        <x-jet-button wire:click="updateVehicle" class="">
            {{ __('Save') }}
        </x-jet-button>
        
    </form>


    <div name="actions">
        <x-jet-action-message class="mr-3" on="saved">
            {{ __('Saved.') }}
        </x-jet-action-message>

        <x-jet-button class="hidden">
            {{ __('Save') }}
        </x-jet-button>
    </div>