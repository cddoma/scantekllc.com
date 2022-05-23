

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
    .input-dirty {
        border-color: rgb(185 28 28)!important;
    }
</style>
        <!-- Team Name -->
@php 
    $autofocus = !empty($this->ro_id) ? ' autofocus ' : '';
@endphp
            

<div class="block h-4">
    <x-jet-action-message class="block text-center"  on="saved">
        <div style="z-index:1000; float:center;" class="bg-indigo-50 border-l-4 border-indigo-400  p-1 my-1" role="alert">
        <p class="font-bold">Saved.</p>
        <p></p>
        </div>
    </x-jet-action-message>
</div>
        <div class="block text-center mt-3 mb-3 pt-3 mx-auto w-full border bg-white shadow shadow-md rounded" style="">
                @if(\Auth::user()->super_admin)
                <div class="col-span-6 sm:col-span-6 block">
                    <div class="mr-2">
                        <x-jet-label  style="cursor:pointer;" for="team_id" value="{{ __('Shop') }}" />
                    </div>
                        <select id="team_id"
                                    name="team_id"
                                    class="mt-1 rounded"
                                    wire:dirty.class="input-dirty"
                                    wire:model.defer="team_id"
                                    value="{{ $team_id }}"
                                    onchange="Livewire.emit('updateVehicle');"
                        >
                            <option selected value=""></option>
                            @foreach($teams as $team)
                                <option value="{{ $team['id'] }}" >{{ $team['name'] }}</option>
                            @endforeach
                        </select>
                </div>
                <hr class="mt-5 mb-0">
                @endif
            

            <div class="col-span-6 sm:col-span-3 inline-block">
                <div class="mr-2">
                    <x-jet-label class="" for="ro" value="{{ __('RO') }}" />
                </div>
                <div class="inline-block"  style="">
                    <x-jet-input id="ro"
                                type="text"
                                class="mt-1"
                                wire:dirty.class="input-dirty"
                                wire:model.defer="ro_num"
                                value="{{ $ro_num }}"
                                placeholder="RO#"
                                onchange="Livewire.emit('updateVehicle');"
                                autocomplete="off"
                                />
                    <x-jet-input-error for="ro" class="mt-2" />
                </div>
            </div>

            <div class="inline-block mt-2">
                <div class="col-span-6 sm:col-span-3 inline-block">
                <div class="mr-2">
                    <x-jet-label for="service_advisor" value="{{ __('Service Advisor') }}" />
                </div>
                <div class="inline-block"  style="">
                    @if(!empty($this->team_id))
                        @livewire('r-o.adjuster-list', [
                            'team_id' => $this->team_id
                        ])
                    @endif
                    <x-jet-input id="service_advisor"
                                name="service_advisor"
                                wire:model.defer="service_advisor"
                                value="{{ $service_advisor }}"
                                wire:dirty.class="input-dirty"
                                onchange="Livewire.emit('updateVehicle');"
                                placeholder="Service Advisor"
                                type="text"
                                list="adjusterOptions"
                                class="mt-1"
                    />
                    <x-jet-input-error for="service_advisor" class="mt-2" />
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
                                    wire:dirty.class="input-dirty"
                                    onchange="Livewire.emit('updateVehicle');"
                                    value="{{ $technician }}"
                                    name="technician"
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
                                    value="{{ $search }}"
                                    onblur="Livewire.emit('updateVehicle');"
                                    list="vehicleOptions"
                                    class="mt-1 w-full sm:max-w-sm"
                                    wire:dirty.class="input-dirty"
                                    style=""
                                    title="{{ $this->search ?? '' }}"
                                    wire:model="search"
                                    placeholder="[YEAR MAKE MODEL] or [VIN]"
                        />
                        @livewire('vehicles.select-list', ['vehicleId' => $this->ro->vehicle->id ?? 0])
                        <x-jet-input-error for="name" class="mt-2 inline-block" />
                        <div id="vehicleIds" class="hidden inline-block">
                            <x-jet-input id="vehicleyear" name="year" type="hidden" wire:model.defer="year"/>
                            <x-jet-input id="vehiclemodel" name="model_id" type="hidden" wire:model.defer="model_id"/>
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

        </div>
            <hr class="mt-5 mb-0">
            <div class="block bg-white shadow shadow-md rounded mt-5 mx-auto p-4"  style="width:max-content;">
                <div class="inline-block text-center" style="width:max-content;">
                    <div class="inline" style="">
                        <datalist id="productOptions">
                            @if(!empty($products))
                                @foreach($products as $product)
                                    <option data-pid="{{ $product['pid'] }}" data-price="{{ $product['pprice'] }}" value="{{ $product['pname'] }}"/>
                                @endforeach
                            @endif
                        </datalist>
                        <x-jet-label class="text-center block" for="product" value="{{ __('Calibration Service') }}" />
                        <x-jet-input id="product"
                                    name="product"
                                    placeholder="Service"
                                    wire:dirty.class="input-dirty"
                                    type="search"
                                    list="productOptions"
                                    class="mt-1 mb-2 inline-block"
                                    style="width:{{ \Auth::user()->super_admin ? 60 : 80}}%;"
                                    onchange="var options = document.getElementById('productOptions').options;
                                            for (let item of options) {
                                                if(item.value == document.getElementById('product').value) {
                                                    document.getElementById('product_price').value = item.getAttribute('data-price');
                                                }
                                            }
                                    "
                                    wire:model.defer="product"
                        />
                    </div>
                        @if(\Auth::user()->super_admin)
                        <x-jet-input id="product_price"
                                    name="product_price"
                                    placeholder="Price"
                                    max="9999.99"
                                    step="0.01"
                                    type="text"
                                    wire:dirty.class="input-dirty"
                                    class="mt-1"
                                    style="width:6.5em;"
                                    wire:model.defer="product_price"
                        />
                        @endif
                        <button type="button" class=" mt-2 mr-2 sm:mr-5 items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition" wire:click="addProduct(document.getElementById('product').value, document.getElementById('productOptions').options[0].getAttribute('data-pid'), (document.getElementById('product_price').value == '' ? document.getElementById('productOptions').options[0].getAttribute('data-price') : document.getElementById('product_price').value));">+</button>
                        @if(!empty($ro_dbid) && \App\Models\RepairOrderProduct::where('repair_order_id', $ro_dbid)->count() > 0)
                            @livewire('r-o-products-table', [
                                'ro_id' => $ro_dbid
                            ])
                        @endif

                </div>
            </div>
            

                <x-jet-button id="save" class="mt-3 ml-0" onclick="Livewire.emit('updateVehicle', '1');">
                    {{ __('Save') }}
                </x-jet-button>
            <x-jet-input wire:model="savebtn"  id="savebtn" type="hidden"/>

        

        <!-- <div class="col-span-6 sm:col-span-4">
            <label class="pt-1 mt-2 bg-gray-800 p-2 border inline-block text-white text-center" for="color" title="{{ __('Vehicle Color') }}">
                {{ __('Vehicle Color') }}
                <input id="color" name="color" wire:model.defer="state.color" type="color" class="mx-auto text-center inline-block">
            </label>
        </div> -->
        
    </form>


    <!-- <div name="actions">
        <x-jet-action-message class="mr-3" on="saved">
            {{ __('Saved.') }}
        </x-jet-action-message>

        <x-jet-button class="hidden">
            {{ __('Save') }}
        </x-jet-button>
    </div> -->
<script>
function point() {
    var inputs = document.getElementsByTagName('input');
    for (let input of inputs) {
        if(input.value == '' || input.value == null) {
        console.log(input);
            input.focus();
            input.scrollIntoView();
            break;
        }
    }
}
(function() {
    point();

})();
</script>