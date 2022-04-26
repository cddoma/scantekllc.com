<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class VehicleMakeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $response = json_decode(Http::get('https://vpic.nhtsa.dot.gov/api/vehicles/getallmakes?format=json'));
        foreach($response->Results as $make) {
            DB::table('vehicle_makes')->insert([
                'name' => $make->Make_Name,
                'vpic_id' => $make->Make_ID
            ]);
        }
    }
}
