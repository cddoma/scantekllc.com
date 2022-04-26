<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\VehicleMake;
use App\Models\VehicleMakeType;

class GetMakeData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $make;

    public $tries = 25;
    public $maxExceptions = 3;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(VehicleMake $make)
    {
        $this->make = $make;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
            $makeId = $this->make->vpic_id;
            $response = json_decode(Http::get("https://vpic.nhtsa.dot.gov/api/vehicles/GetVehicleTypesForMakeId/$makeId?format=json"));
            if($response->Count > 0) {
                foreach($response->Results as $type) {
                    VehicleMakeType::create([
                        'name' => $type->VehicleTypeName,
                        'vpic_make_id' => $makeId,
                        'vpic_id' => $type->VehicleTypeId
                    ]);
                }
            }
    }
}
