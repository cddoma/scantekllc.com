<?php

namespace App\Http\Livewire\Vehicles;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Models\Team;

class SelectList extends Component
{
    public $options;
    public String $searchTerm = '';
    public String $year;
    public String $make;
    public String $model;

    protected $listeners = ['updateVehicleOptions' => 'update'];

    public function update($searchTerm = '')
    {
        $this->searchTerm = !empty($searchTerm) ? $searchTerm : $this->searchTerm;
        $year = '';
        $make = '';
        $model = '';
        $matches = [];

        preg_match('/([0-2][0-9])([0-9][0-9])?( )?([a-z,A-Z,\-,&,\',\.,0-9]+)?( )?([0-9,a-z,A-Z,\-,\',\.,0-9]+)?( )?([0-9,a-z,A-Z,\-,&,\',\.,0-9]+)?/', $this->searchTerm, $matches);

        if(!empty($matches[1])) {
            // first 2 digits of year
            $year = $matches[1];
            if(!empty($matches[2]) && \Str::length($matches[2]) == 2) {
                // last 2 digits of year
                $year .= $matches[2];
            }
            if(empty($matches[2])) {
                $year = '20'.$year;
                $this->year = $year;
            } 
            // make
            if(!empty($year) && !empty($matches[4])  && \Str::length($matches[4]) > 1) {
                $make = $matches[4];
                if(!empty($matches[7]) && !empty($matches[6])) {
                    $make = $matches[4] . ' ' . $matches[6];
                }
                $make = strtoupper($make);
                $this->make = $make;
            } 
            // model
            if(!empty($year) && !empty($make) && !empty($matches[5])) {
                if(!empty($matches[7])) {
                    $model = $matches[8] ?? '';
                }
                if(empty($matches[7])) {
                    $model = $matches[6] ?? '';
                } 
                $model = strtoupper($model);
                $this->model = $model;
            } 
            // get options
            if(!empty($year)) {
                if(!empty($make)) {
                    if($make_id = $this->makeExists($make)) {
                        // get models
                        $this->options = $this->getVehicleModels($year, $make_id, trim($model));
                    } else {
                        // get makes
                        $this->options = $this->getVehicleMakes($year, $make);
                    } 
                } else {
                    // default makes
                    $this->options = $this->getVehicleMakesShortlist($year);
                } 
            }
        } 
    }

    public function mount($searchTerm = '')
    {
        // $this->searchTerm = $searchTerm;
        // $this->options = $this->getVehicleMakesShortlist();
    }

    public function render()
    {
        if(empty($this->options)) {
            $this->update($this->searchTerm);
        }
        return view('livewire.vehicles.select-list');
    }

    private function getVehicleMakesShortlist($year = '', $make = '')
    {
        $make = strtoupper($make);
        $return = [];
        $makes = [
            "36" => "ACURA",
            "53" => "ALFA ROMEO",
            "1" => "ASTON MARTIN",
            "131" => "AUDI",
            "132" => "BENTLEY",
            "13" => "BMW",
            "29" => "BUICK",
            "30" => "CADILLAC",
            "28" => "CHEVROLET",
            "38" => "CHRYSLER",
            "37" => "DODGE",
            "52" => "FIAT",
            "21" => "FORD",
            "33" => "GMC",
            "35" => "HONDA",
            "58" => "HYUNDAI",
            "41" => "INFINITI",
            "96" => "ISUZU",
            "3" => "JAGUAR",
            "59" => "KIA",
            "62" => "LAMBORGHINI",
            "74" => "LEXUS",
            "25" => "LINCOLN",
            "4" => "MASERATI",
            "34" => "MAZDA",
            "10" => "MERCEDES-BENZ",
            "26" => "MERCURY",
            "17" => "MINI",
            "42" => "MITSUBISHI",
            "39" => "NISSAN",
            "133" => "PORSCHE",
            "6" => "ROLLS ROYCE",
            "123" => "SAAB",
            "80" => "SUBARU",
            "2" => "TESLA",
            "9" => "TOYOTA",
            "43" => "VOLKSWAGEN",
            "46" => "VOLVO",
        ];
        foreach ($makes as $id => $name) {
            if(trim($make) == '' || str_starts_with($name, $make) || $name == $make){
                $vehicleMake = [];
                $vehicleMake['make_id'] = $id;
                $vehicleMake['model_id'] = '';
                $vehicleMake['year'] = $year;
                $vehicleMake['value'] = $year . ' ' . $name . ' ';
                $return[] = $vehicleMake;
            }
        }
        return $return;
    }

    private function getVehicleMakes($year, $make)
    {
        $return = [];
        foreach( DB::table('vehicle_makes')
            // ->join('vehicle_model_years', 'vehicle_makes.vpic_id', '=', 'vehicle_model_years.vpic_make_id')
            // ->join('vehicle_make_types', 'vehicle_makes.vpic_id', '=', 'vehicle_make_types.vpic_make_id')
            // ->whereIn('vehicle_make_types.vpic_id', [2,3,5,7])
            ->where([
                // ['vehicle_model_years.year', $year],
                ['vehicle_makes.name', 'LIKE', "{$make}%"],
            ])
            ->groupBy('vehicle_makes.vpic_id')
            ->orderBy('vehicle_makes.name')
            ->select('vehicle_makes.vpic_id as make_id', 'vehicle_makes.name as make')
            ->get()
            as $row 
        ) {
            $vehicleMake = [];
            $vehicleMake['make_id'] = $row->make_id;
            $vehicleMake['model_id'] = '';
            $vehicleMake['year'] = $year;
            $vehicleMake['value'] = $year . ' ' . strtoupper($row->make) . ' ';
            $return[] = $vehicleMake;
        }
        return $return;
    }

    private function getVehicleModels($year, $make_id, $model = '')
    {
        $return = [];
        $count = 0;
        $models = DB::table('vehicle_models')
            ->join('vehicle_makes', 'vehicle_models.vpic_make_id', '=', 'vehicle_makes.vpic_id')
            // ->join('vehicle_model_years', 'vehicle_makes.vpic_id', '=', 'vehicle_model_years.vpic_make_id')
            // ->join('vehicle_make_types', 'vehicle_makes.vpic_id', '=', 'vehicle_make_types.vpic_make_id')
            // ->whereIn('vehicle_make_types.vpic_id', [2,3,5,7])
            ->where([
                // ['vehicle_model_years.year', $year],
                ['vehicle_makes.vpic_id', '=', $make_id],
                ['vehicle_models.name', 'LIKE', "{$model}%"],
            ])
            ->groupBy('vehicle_models.id')
            ->select('vehicle_makes.vpic_id as make_id', 'vehicle_models.vpic_id as model_id', 'vehicle_makes.name as make', 'vehicle_models.name as model')
            ->get();
        $count = count($models);
        foreach( $models as $row ) {
            $vehicleMake = [];
            $vehicleMake['make_id'] = $make_id;
            $vehicleMake['model_id'] = $row->model_id;
            $vehicleMake['year'] = $year;
            $vehicleMake['value'] = $year . ' ' . strtoupper($row->make) . ' ' . strtoupper($row->model);
            $return[] = $vehicleMake;
            if($count == 1) {
                $this->emit('updateVehicleIds', $year, $make_id, $row->model_id);
            }
        }
        return $return;
    }

    public function makeExists($make)
    {
        return DB::table('vehicle_makes')
            // ->join('vehicle_make_types', 'vehicle_makes.vpic_id', '=', 'vehicle_make_types.vpic_make_id')
            // ->whereIn('vehicle_make_types.vpic_id', [2,3,5,7,10])
            ->where('vehicle_makes.name', $make)
            // ->groupBy('vehicle_makes.vpic_id')
            ->select('vehicle_makes.vpic_id')
            ->first()
            ->vpic_id ?? false;
    }
}
