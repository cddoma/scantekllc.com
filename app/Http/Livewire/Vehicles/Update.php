<?php

namespace App\Http\Livewire\Vehicles;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Vehicle;
use App\Models\VehicleMeta;
use App\Models\VehicleMake;
use App\Models\VehicleModel;
use App\Jobs\GetVinData;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\VehicleModelYear;
use Illuminate\Support\Facades\Http;

class Update extends Component
{
    protected $vehicle;
    public $state = [];
    public $options = [];
    public $make;
    public $model;

    protected $listeners = ['updateVehicleIds' => 'updateVehicleIds', 'refresh' => '$refresh'];

    public function mount($vehicleId = '')
    {
        $this->vehicle = Vehicle::findOrNew($vehicleId);
        $this->state = $this->vehicle->withoutRelations()->toArray();
        $this->state['name'] = $this->state['name'] ?? '';
    }

    public function render()
    {
        $this->emit('updateVehicleOptions', $this->state['name']);
        return view('vehicles.update-form');
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

    public function updateVehicleIds($year, $make_id, $model_id)
    {
        $this->state['year'] = $year;
        $this->state['vpic_make_id'] = $make_id;
        $this->state['vpic_model_id'] = $model_id;
        $this->skipRender();
    }

    public function updateVehicle()
    {
        // $this->resetErrorBag();

        if(empty($this->state['id'])) {
            // VehicleModelYear::where([[''],[]])
            if(!empty($this->state['vpic_make_id'])) {
                $make = VehicleMake::where('vpic_id', $this->state['vpic_make_id'])->first();
            }
            if(!empty($this->state['vpic_model_id'])) {
                $model = VehicleModel::where('vpic_id', $this->state['vpic_model_id'])->first();
            }
            $this->vehicle = Vehicle::create(
                [
                    'team_id' => \Auth::user()->current_team_id,
                    'name' => $this->state['name'] ?? null,
                    'year' => $this->state['year'] ?? null,
                    'make' => $make->name ?? null,
                    'model' => $model->name ?? null,
                    'trim' => $this->state['trim'] ?? null,
                    'vin' => $this->state['vin'] ?? null,
                    'vpic_make_id' => $this->state['vpic_make_id'] ?? null,
                    'vpic_model_id' => $this->state['vpic_model_id'] ?? null,
                ]
            );
            $this->emit('saved');
            if(!empty($this->vehicle->vin)) {
                $this->getVinData();
            }

            return redirect()->route('ro.create', ['vehicleId' => $this->vehicle->id]);
        } else {
            $this->vehicle = Vehicle::findOrFail($this->state['id']);
            $this->vehicle->name = $this->state['name'];
            $this->vehicle->year = $this->state['year'];
            $this->vehicle->make = $this->state['make'];
            $this->vehicle->model = $this->state['model'];
            $this->vehicle->trim = $this->state['trim'];
            $this->vehicle->vpic_make_id = $this->state['vpic_make_id'];
            $this->vehicle->vpic_model_id = $this->state['vpic_model_id'];
            $this->vehicle->save();
            $this->emit('saved');
            if($this->vehicle->vin !== $this->state['vin']) {
                $this->vehicle->vin = $this->state['vin'];
                $this->vehicle->save();
                $this->getVinData();
            }
        }

        return redirect()->route('vehicles.show', $this->vehicle->id);
    }

    private function getVinData()
    {
        $vin = $this->vehicle->vin;
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
            VehicleMeta::where([['vehicle_id', $this->vehicle->id],['source', 'vpic']])->delete();
            foreach($response->Results as $data) {
                if(!empty($data->Variable) && !empty($data->Value)) {
                    if('not applicable' == strtolower(trim($data->Value))) {
                        continue;
                    }
                    switch ($data->Variable) {
                        case 'Make':
                            $this->vehicle->make = $data->Value;
                            $this->vehicle->vpic_make_id = $data->ValueId;
                            break;
                        
                        case 'Model':
                            $this->vehicle->model = $data->Value;
                            $this->vehicle->vpic_model_id = $data->ValueId;
                            break;
                        
                        case 'Model Year':
                            $this->vehicle->year = $data->Value;
                            break;
                        
                        case 'Trim':
                            $this->vehicle->trim = $data->Value;
                            break;
                    }
                    $meta = VehicleMeta::updateOrCreate(
                        ['vehicle_id' => $this->vehicle->id, 'source' => 'vpic', 'key' => $data->Variable],
                        ['value' => $data->Value]
                    );
                }
            }
            $this->vehicle->name = $this->vehicle->year . ' ' . $this->vehicle->make . ' ' . $this->vehicle->model . ' ' . $this->vehicle->trim;
            $this->vehicle->save();
        }
    }
}
