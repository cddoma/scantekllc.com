<?php

namespace App\Http\Livewire\Vehicles;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Models\Team;
use App\Models\Vehicle;

class SelectList extends Component
{
    public $options;
    public string $searchTerm = '';
    public string $year;
    public string $make;
    public string $model;
    public int $model_id;
    public int $vehicleId;

    protected $listeners = ['updateVehicleOptions' => 'update'];

    public function update($searchTerm = '')
    {
        $this->searchTerm = $searchTerm;
        $matches = [];
        preg_match('/([0-2][0-9])([0-9][0-9]?)?( )?(.*)?/', $this->searchTerm, $matches);
        if(strlen($this->searchTerm) == 17 && empty($matches[3])) {
        }
        if(!empty($matches[1])) {
            // first 2 digits of year
            $year = $matches[1];
            if(!empty($matches[2])) {
                if(\Str::length($matches[2]) == 2) {
                    // last 2 digits of year
                    $year .= $matches[2];
                    $this->year = $year;
                } else {
                    $this->options = $this->getYears();
                }
            } else {
                if(!empty($matches[3])) {
                    $year = '20'.$year;
                    $this->year = $year;
                } 
            } 
            if(isset($matches[3]) || \Str::length($this->year) == 4) {
                // make
                if(isset($matches[4])) {
                    $make = trim($matches[4]);
                    $this->options = $this->getVehicleMakes($year, $make);
                }
            }
        } else {
            $this->options = $this->getYears();
        }
    }

    private function getYears()
    {
        $options = [];
        for ($year=intval(date("Y")); $year >= intval(date("Y")-30); $year--) { 
            $options[] = [ 'year' => $year, 'value' => $year.' ', 'make_id' => '', 'model_id' => '' ];
        }
        return $options;
    }

    public function mount($vehicleId)
    {
        // $this->searchTerm = $searchTerm;
        $this->vehicleId = $vehicleId;
        $this->options = $this->getYears();
    }

    public function render()
    {
        if(empty($this->options)) {
            $this->update($this->searchTerm);
        }
        return view('livewire.vehicles.select-list');
    }

/*
    private function getVehicleMakesShortlist($year = '', $make = '')
    {
        $return = [];
        return $return;
        $make = strtoupper($make);
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
                $vehicleMake['make_id'] = $name;
                $vehicleMake['model_id'] = '';
                $vehicleMake['year'] = $year;
                $vehicleMake['value'] = $year . ' ' . $name . ' ';
                $return[] = $vehicleMake;
            }
        }
        return $return;
    }
*/

    private function getVehicleMakes($year, $make)
    {
        $return = [];
        $count = 0;
        $makes = DB::table('tbl_models')
            ->where([
                ['model_sold_in_us', 1],
                ['model_year', $year],
                [DB::raw('CONCAT(model_make_display, " ", model_name)'), 'LIKE', "{$make}%"],
            ])
            ->groupBy('model_make_id')
            ->orderBy('model_make_id', 'asc')
            ->select('model_make_display as make', 'model_make_id as make_id')
            ->limit(40)
            ->get();
        $count = count($makes);
        foreach($makes as $row) {
            if($count == 1) {
                $this->year = $year;
                $this->make = $row->make;
                $this->make_id = $row->make_id;
                return $this->getVehicleModels($year, $row->make_id, $make);
            }
            $vehicleMake = [];
            $vehicleMake['make'] = $row->make;
            $vehicleMake['year'] = $year;
            $vehicleMake['value'] = $year . ' ' . $row->make . ' ';
            $return[] = $vehicleMake;
        }
        return $return;
    }

    private function getVehicleModels($year, $make_id, $model = '')
    {
        $return = [];
        $count = 0;
        $models = DB::table('tbl_models')
            ->where([
                ['model_sold_in_us', 1],
                ['model_year', $year],
                ['model_make_id', $make_id],
                [DB::raw('CONCAT(model_make_display, " ", model_name)'), 'LIKE', "%{$model}%"],
            ])
            
            ->orderBy('model_name', 'asc')
            ->distinct('model_name')
            ->select('model_make_id as make_id', 'model_make_display as make', 'model_name as model')
            ->limit(40)
            ->get();
        $count = count($models);
        foreach( $models as $row ) {
            if($count == 1) {
                $this->year = $year;
                $this->make = $row->make;
                $this->make_id = $row->make_id;
                $this->model = $row->model;
                // $this->emit('updateVehicleIds', $year, $row->make_id, $row->model_id);
                // return $this->getVehicleTrims($year, $row->make, $this->searchTerm);
            }
            $vehicleMake = [];
            $vehicleMake['make_id'] = $make_id;
            $vehicleMake['model'] = $row->model;
            $vehicleMake['year'] = $year;
            $vehicleMake['value'] = $year . ' ' . $row->make . ' ' . $row->model;
            $return[] = $vehicleMake;
        }
        return $return;
    }

    private function getVehicleTrims($year, $make_id, $search)
    {
        $return = [];
        $count = 0;
        $models = DB::table('tbl_models')
            ->where([
                ['model_sold_in_us', 1],
                ['model_year', $year],
                ['model_make_id', $make_id],
                [DB::raw('CONCAT(model_year, " ", model_make_display, " ", model_name, " ", model_trim)'), 'LIKE', "{$search}%"],
            ])
            ->groupBy('model_name', 'model_trim')
            ->orderBy('model_name', 'asc')
            ->limit(25)
            ->select('model_id as model_id', 'model_make_display as make', 'model_name as model')
            ->get();
        $count = count($models);
        foreach( $models as $row ) {
            $vehicleMake = [];
            $vehicleMake['make_id'] = $row->make;
            $vehicleMake['model_id'] = $row->model_id;
            $vehicleMake['model'] = $row->model;
            $vehicleMake['year'] = $year;
            $vehicleMake['value'] = $year . ' ' . $row->make . ' ' . $row->model;
            if($count == 1) {
                if($this->vehicleId > 0) {
                    $vehicle = Vehicle::findOrFail($this->vehicleId);
                    $vehicle->year = $year;
                    $vehicle->make = $row->make;
                    $vehicle->model = $row->model;
                    $vehicle->model_id = $row->model_id;
                    $vehicle->save(); 
                }

                $this->year = $year;
                $this->make = $row->make;
                $this->model = $row->model;
                $this->model_id = $row->model_id;
                $this->emit('updateVehicleId', $this->model_id);
            }
            $return[] = $vehicleMake;
        }
        return $return;
    }

    public function makeExists($year, $make)
    {
        return DB::table('tbl_models')
            ->where('model_make_display', $make)
            ->where([
                ['model_sold_in_us', 1],
                ['model_year', $year],
                ['model_make_id', $make],
            ])
            ->select('model_make_display')
            ->first()->model_make_display ?? false;
    }
}
