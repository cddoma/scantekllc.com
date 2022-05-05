<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\VehicleMake;
use App\Jobs\GetModelYear;
use App\Models\VehicleModelYear;

class VehicleModelYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $startYear = 2000;//intval(date("Y")) + 2;
        for ($year=$startYear; $year >= 1990; $year--) { 
            $makes = VehicleMake::whereIn('vpic_id', function ($query) {
                // $query->selectRaw('distinct(vpic_make_id)')->from('vehicle_make_types')->where('vpic_id', [2,3,7]);
                $query->selectRaw('distinct(vpic_make_id)')->from('vehicle_make_types')->where('vpic_id', [1,5,6,9,10,13]);
            })->get();
            foreach($makes as $make) {
                GetModelYear::dispatch($make->vpic_id, $year);
            }
        }
    }
}
