<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class VehicleManufacturerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=1; $i < 1000; $i++) { 
            $response = json_decode(Http::get("https://vpic.nhtsa.dot.gov/api/vehicles/getallmanufacturers?format=json&page=$i"));
            if($response->Count == 0) {
                break;
            }
            foreach($response->Results as $m) {
                DB::table('vehicle_manufacturers')->insert([
                    'country' => $m->Country,
                    'name' => empty($m->Mfr_CommonName) ? $m->Mfr_Name : $m->Mfr_CommonName,
                    'full_name' => $m->Mfr_Name,
                    'vpic_id' => $m->Mfr_ID
                ]);
            }
        }
    }
}
