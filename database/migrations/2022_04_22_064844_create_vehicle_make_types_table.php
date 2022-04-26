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
        Schema::create('vehicle_make_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vpic_id');
            $table->unsignedBigInteger('vpic_make_id');
            $table->tinyText('name');
            //$table->unique('vpic_id', 'vpic_make_id');
        });
        Artisan::call('db:seed', ['--class' => 'VehicleMakeTypeSeeder']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicle_make_types');
    }
};
