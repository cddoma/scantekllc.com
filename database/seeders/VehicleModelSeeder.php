<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\VehicleMake;

class VehicleModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach(VehicleMake::all() as $make) {
            $makeId = $make->vpic_id;
            $response = json_decode(Http::get("https://vpic.nhtsa.dot.gov/api/vehicles/GetModelsForMakeId/$makeId?format=json"));
            foreach($response->Results as $m) {
                DB::table('vehicle_models')->insert([
                    'name' => $m->Model_Name,
                    'vpic_make_id' => $m->Make_ID,
                    'vpic_id' => $m->Model_ID
                ]);
            }
        }
    }
}
