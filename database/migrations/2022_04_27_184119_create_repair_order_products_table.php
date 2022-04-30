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
        Schema::create('repair_order_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('repair_order_id')->index();
            $table->unsignedBigInteger('product_id')->index();
            $table->float('price', 8, 2)->nullable()->default(null);
            $table->tinyText('name')->nullable()->default(null);
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
        Schema::dropIfExists('repair_order_products');
    }
};