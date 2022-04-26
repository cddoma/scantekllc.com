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
use App\Models\VehicleModelYear;

class GetModelYear implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $url;
    public $year;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $makeId, int $year)
    {
        $this->year = $year;
        $this->url = "https://vpic.nhtsa.dot.gov/api/vehicles/GetModelsForMakeIdYear/makeId/$makeId/modelyear/$year?format=json";
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $response = json_decode(Http::get($this->url));
        if(!empty($response->Count)) {
            foreach($response->Results as $m) {
                VehicleModelYear::create([
                    'year' => $this->year,
                    'vpic_make_id' => $m->Make_ID,
                    'vpic_model_id' => $m->Model_ID
                ]);
            }
        }
    }
}
