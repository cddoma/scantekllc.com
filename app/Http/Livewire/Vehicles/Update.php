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
    public $model_id;
    public $ro_id;
    public $team_id;
    public $ro_num;
    public $technician;
    public $service_advisor;
    public $product;
    public $product_price;
    public $savebtn;

    protected $listeners = [
        'updateVehicle' => 'updateVehicle',
        'updateVehicleId' => 'updateVehicleId',
        'refresh' => '$refresh'
    ];

    public function mount($ro_id)
    {

        $this->ro_id = $ro_id;
        $this->ro = RO::getByRO($this->ro_id);
        $this->ro_id = !empty($this->ro->ro) ? $this->ro->ro : ($this->ro->id ?? '');
        $this->team_id = $this->ro->team_id ?? \Auth::user()->current_team_id;
        $this->ro_num = $this->ro->ro ?? '';
        $this->technician = $this->ro->technician ?? '';
        $this->vehicle_id = $this->ro->vehicle->id ?? 0;
        $this->model_id = $this->ro->vehicle->model_id ?? 0;
        $this->vin = $this->ro->vehicle->vin ?? '';
        $this->search = $this->ro->vehicle->name ?? '';
        $this->service_advisor = $this->ro->service_advisor ?? '';
        $this->product = 
        $this->product_price = '';
    }

    public function render()
    {
        $this->ro = RO::getByRO($this->ro_id);
        if(!empty($this->search)) {
            if(strlen($this->search) == 17) {
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
            ->orderBy(DB::raw('count(repair_orders.id)'), 'desc')
            ->select('teams.id', 'teams.name')
            ->get()->toArray();
        $products = Product::where([['hidden', 0], ['active', 1]])->select('name as pname', 'products.id as pid', 'default_price as pprice')->get()->toArray();
        return view('vehicles.update-form', [
            'ro_dbid' => $this->ro->id,
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

    public function addProduct($product, $product_id = 0, $price = 0)
    {
        $this->ro = $this->ro ?? RO::getByRO($this->ro_id);
        $price = $price;
        ROProduct::create(['repair_order_id' => $this->ro->id, 'name' => $product, 'product_id' => $product_id, 'price' => $price]);
        $this->product = '';
        $this->product_price = '';
        //$this->skipRender();
        $this->emit('refreshROProducts');
    }

    public function updateProduct($product_id)
    {
        $this->product_id = $product_id;
    }

    public function updateServiceAdvisor($adjuster)
    {
        $this->service_advisor = ucwords($adjuster);
        $this->skipRender();
    }

    public function updateVehicleId($model_id)
    {
        $this->model_id = $model_id;
    /*
        $vehicle = Vehicle::findOrFail($vehicle_id);
        $model = DB::table('tbl_models')
            ->where([
                ['model_sold_in_us', 1],
                ['model_id', $model_id],
            ])
            ->select('model_year as year', 'model_make_display as make', 'model_id', 'model_name as model', 'model_trim as trim')
            ->get();
        $model = (count($model) == 1) ? $model[0] : [];
        if(!empty($model->model_id) && !empty($this->ro->vehicle->id)) {
            $this->year = $model->year;
            $this->model_id = $model->model_id;
            $vehicle->model_id = $model->model_id;
            $vehicle->year = $model->year;
            $vehicle->make = $model->make;
            $vehicle->model = $model->model;
            $vehicle->trim = $model->trim;
            $vehicle->save();
            $this->emit('saved');
        }
        //$this->skipRender();
    */
    }

    public function updateVehicle($returnToIndex = 0)
    {
        // $this->resetErrorBag();
        $this->ro = RO::getByRO($this->ro_id);

        if(!empty($this->ro->vehicle->id)) {
            if($this->ro->vehicle->name !== $this->search || empty($this->ro->vehicle->model_id)) {
                // VehicleModelYear::where([[''],[]])
                $this->ro->vehicle->name = $this->search;
                $this->ro->vehicle->team_id = $this->team_id;
                $this->ro->vehicle->save();
                // $this->updateVehicleId($this->model_id);
                /*
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
                */
            }
        }
        $old_ro = $this->ro->ro;
        $old_team = $this->ro->team_id;
        $this->ro->ro = $this->ro_num;
        $this->ro->technician = ucwords($this->technician);
        $this->ro->service_advisor = ucwords($this->service_advisor);
        $this->ro->team_id = $this->team_id;
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
        if((!empty($this->ro->ro) && $old_ro !== $this->ro->ro && $old_ro !== $this->ro->id) || $old_team !== $this->team_id) {
            $ro_id = !empty($this->ro->ro) ? $this->ro->ro : ($this->ro->id ?? '');
            return redirect()->route('ro.show', $ro_id);
        }
        if($returnToIndex > 0) {
            return redirect()->route('ro.index');
        }

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
