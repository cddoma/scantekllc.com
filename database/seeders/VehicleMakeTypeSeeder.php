<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\VehicleMake;
use App\Jobs\GetMakeData;

class VehicleMakeTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $startYear = intval(date("Y")) + 2;
        foreach(VehicleMake::all() as $make) {
            GetMakeData::dispatch($make);
        }
    }
}
