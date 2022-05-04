<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use App\Models\Vehicle;
use App\Models\VehicleMeta;

class GetVinData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $vehicle;

    public $tries = 25;
    public $maxExceptions = 3;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Vehicle $vehicle)
    {
        $this->vehicle = $vehicle;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $vin = $this->vehicle->vin;
        // 17 CHARACTER VIN MAX
        if(strlen($vin) > 17) {
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
            $this->vehicle->name = $this->vehicle->year . ' ' . $this->vehicle->make . ' ' . $this->vehicle->model;
            $this->vehicle->save();
            $this->emit('vehicleVinDataCollected');
        }
    }
}
