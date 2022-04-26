<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_manufacturers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vpic_id');
            $table->tinyText('country');
            $table->tinyText('name');
            $table->tinyText('full_name');
        });
        Artisan::call('db:seed', ['--class' => 'VehicleManufacturerSeeder']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicle_manufacturers');
    }
};
