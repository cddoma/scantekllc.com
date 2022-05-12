<?php

namespace App\Http\Livewire\Vehicles;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Team;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleMeta;
use App\Models\VehicleMake;
use App\Models\VehicleModel;
use App\Models\Product;
use App\Models\RepairOrder as RO;
use App\Models\RepairOrderProduct as ROProduct;
use App\Jobs\GetVinData;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\VehicleModelYear;
use Illuminate\Support\Facades\Http;

class Update extends Component
{
    protected $vehicle;
    protected $ro;
    // public $state = [];
    // public $options = [];
    // public $make;
    // public $model;
    public $search = '';
    public $vehicle_id;
    public $vin;
    public $year;
    public $vpic_make_id;
    public $vpic_model_id;
    public $ro_id;
    public $team_id;
    public $ro_num;
    public $technician;
    public $adjuster_id;
    public $product;
    public $product_price;

    protected $listeners = ['updateVehicleIds' => 'updateVehicleIds', 'refresh' => '$refresh'];

    public function mount($ro_id)
    {
        /*
        $this->product = 
        $this->product_price = 
        $this->ro_num = 
        $this->vin = 
        $this->vpic_make_id = 
        $this->vpic_model_id = 
        $this->adjuster_id = 
        $this->technician = '';
        */
        $this->ro_id = $ro_id;
        $this->ro = RO::where('id', $this->ro_id)->first();
        $this->team_id = !\Auth::user()->super_admin ? \Auth::user()->current_team_id : ( $this->ro->team_id ?? 0);
        $this->ro_num = $this->ro->ro ?? '';
        $this->technician = $this->ro->technician ?? '';
        $this->vehicle_id = $this->ro->vehicle->id ?? 0;
        $this->vin = $this->ro->vehicle->vin ?? '';
        $this->search = $this->ro->vehicle->name ?? '';
        $this->ro_id = $this->ro->id ?? 0;
        $this->adjuster_id = $this->ro->adjuster;
    }

    private function newRO()
    {
        $vehicle = Vehicle::create(['team_id' => $this->team_id]);
        $this->ro = RO::create(['team_id' => $this->team_id, 'created_by' => \Auth::user()->id, 'vehicle_id' => $vehicle->id]);
                
        return $this->ro->id;
    }
    public function render()
    {
        if(!empty($this->team_id) && empty($this->ro->id) && (!empty($this->ro_num) || !empty($this->technician) || !empty($this->adjuster_id) || !empty($this->search) || !empty($this->vpic_make_id) || !empty($this->vpic_model_id))) {
            $this->newRO();
        }
        if(!empty($this->search)) {
            preg_match('/([0-2][0-9])([0-9][0-9])?( )?([a-z,A-Z,\-,&,\',\.,0-9]+)?( )?([0-9,a-z,A-Z,\-,\',\.,0-9]+)?( )?([0-9,a-z,A-Z,\-,&,\',\.,0-9]+)?/', $this->search, $matches);

            if(strlen($this->search) == 17 && empty($matches[3])) {
                $this->getVinData($this->search);
            } else {
                $this->emit('updateVehicleOptions', $this->search);
            }
        }
        if(!empty($this->team_id) && $this->team_id !== \Auth::user()->current_team_id ) {
            $this->emit('updateAdjusterOptions', $this->team_id);
        }
        $teams = Team::select('teams.name', 'teams.id')
            ->leftJoin('repair_orders', 'teams.id', '=', 'repair_orders.team_id')
            ->groupBy('teams.id')
            ->orderBy(DB::raw('count(repair_orders.id)'), 'desc')->get()->toArray();
        $products = Product::where([['hidden', 0], ['active', 1]])->select('name', 'id')->get()->toArray();
        return view('vehicles.update-form', [
            'teams' => $teams,
            'products' => $products
        ]);
    }

    public function rules()
    {
        $min = 1990;
        // 2 Years from now
        $max = Carbon::now()->format('Y') + 2;
        $max_sh = $max - 2000;
        return [
            'year' => "nullable|max:$max|digits:2|digits:4",
            'vin' => 'nullable|size:17',
        ];
    }

    public function addProduct($product, $price = '')
    {
        ROProduct::create(['repair_order_id' => $this->ro->id, 'name' => $product, 'product_id' => 0, 'price' => $price]);
        $this->product = '';
        $this->product_price = '';
        $this->emit('refreshROProducts');
    }

    public function updateProduct($product_id)
    {
        $this->product_id = $product_id;
    }

    public function updateAdjuster($user_id)
    {
        $this->adjuster_id = $user_id;
        $this->skipRender();
    }

    public function updateVehicleIds($year, $make_id, $model_id)
    {
        $this->year = $year;
        $this->vpic_make_id = $make_id;
        $this->vpic_model_id = $model_id;
        $this->skipRender();
    }

    public function updateVehicle()
    {
        // $this->resetErrorBag();

        if(empty($this->ro->vehicle->id)) {
            $this->ro = RO::findOrFail($this->ro_id);
        }
        if($this->ro->vehicle->name !== $this->search) {
            // VehicleModelYear::where([[''],[]])
            $this->ro->vehicle->name = $this->search;
            if(!empty($this->vpic_make_id)) {
                if($make = VehicleMake::where('vpic_id', $this->vpic_make_id)->first()) {
                    $this->ro->vehicle->make = $make->name;
                    $this->ro->vehicle->vpic_make_id = $this->vpic_make_id;
                }
            }
            if(!empty($this->vpic_model_id)) {
                if( $model = VehicleModel::where('vpic_id', $this->vpic_model_id)->first() ) {
                    $this->ro->vehicle->model = $model->name;
                    $this->ro->vehicle->vpic_model_id = $this->vpic_model_id;
                }
            }
            $this->ro->vehicle->year = $this->year;
            // $this->ro->vehicle->trim = $this->state['trim'];
            $this->ro->vehicle->save();
            $this->emit('saved');
        }
        $this->ro->ro = $this->ro_num;
        $this->ro->technician = $this->technician;
        $this->ro->adjuster = $this->adjuster_id;
        if(!empty($this->product_id)) {
            ROProduct::create([
                'repair_order_id' => $this->ro->id,
                'product_id' => $this->product_id ?? 0,
                'name' => $this->product,
            ]);
            $this->emit('refreshROProducts');
        }
        $this->ro->save();
        $this->emit('saved');

        // return redirect()->route('ro.show', $this->ro->id);
    }

    private function getVinData($vin)
    {
        // 17 CHARACTER VIN MAX
        if(strlen($vin) > 17 || empty($vin)) {
            return;
        }
        // append * if VIN is less than 17 characters
        if(strlen($vin) < 17) {
            $vin .= '*';
        }
        $response = json_decode(Http::get("https://vpic.nhtsa.dot.gov/api/vehicles/decodevinextended/$vin?format=json"));
        if($response->Count > 0) {
            VehicleMeta::where([['vehicle_id', $this->ro->vehicle->id],['source', 'vpic']])->delete();
            foreach($response->Results as $data) {
                if(!empty($data->Variable) && !empty($data->Value)) {
                    if('not applicable' == strtolower(trim($data->Value))) {
                        continue;
                    }
                    switch ($data->Variable) {
                        case 'Make':
                            $this->ro->vehicle->make = $data->Value;
                            $this->ro->vehicle->vpic_make_id = $data->ValueId;
                            break;
                        
                        case 'Model':
                            $this->ro->vehicle->model = $data->Value;
                            $this->ro->vehicle->vpic_model_id = $data->ValueId;
                            break;
                        
                        case 'Model Year':
                            $this->ro->vehicle->year = $data->Value;
                            break;
                        
                        case 'Trim':
                            $this->ro->vehicle->trim = $data->Value;
                            break;
                    }
                    $meta = VehicleMeta::updateOrCreate(
                        ['vehicle_id' => $this->ro->vehicle->id, 'source' => 'vpic', 'key' => $data->Variable],
                        ['value' => $data->Value]
                    );
                }
            }
            $this->ro->vehicle->vin = $vin;
            $this->ro->vehicle->name = $this->ro->vehicle->year . ' ' . $this->ro->vehicle->make . ' ' . $this->ro->vehicle->model . ' ' . $this->ro->vehicle->trim . '  ' . $this->ro->vehicle->vin;
            $this->ro->vehicle->save();
            $this->emit('saved');
            return redirect()->route('ro.show', $this->ro->id);
        }
    }
}
