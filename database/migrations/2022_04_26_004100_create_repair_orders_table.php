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
        Schema::create('repair_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('team_id')->index();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('vehicle_id')->index();
            $table->enum('priority', ['1', '2', '3', '4', '5'])->default('1');
            $table->enum('status', ['requested', 'queued', 'active', 'completed'])->default('requested');
            $table->string('technician')->nullable()->default(null);
            $table->text('user_notes')->nullable()->default(null);
            $table->text('notes')->nullable()->default(null);
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
        Schema::dropIfExists('repair_orders');
    }
};