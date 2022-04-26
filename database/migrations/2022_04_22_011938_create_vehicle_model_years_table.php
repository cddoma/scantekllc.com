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
        Schema::create('vehicle_model_years', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vpic_model_id');
            $table->unsignedBigInteger('vpic_make_id');
            $table->year('year');
        });
        // Artisan::call('db:seed', ['--class' => 'VehicleModelYearSeeder']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicle_model_years');
    }
};
