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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('team_id')->index();
            $table->tinyText('name');
            $table->string('vin', 17)->nullable()->default(null);
            $table->year('year')->nullable()->default(null);
            $table->string('make')->nullable()->default(null);
            $table->string('model')->nullable()->default(null);
            $table->string('trim')->nullable()->default(null);
            $table->unsignedBigInteger('vpic_model_id')->nullable()->default(null);
            $table->unsignedBigInteger('vpic_make_id')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicles');
    }
};
