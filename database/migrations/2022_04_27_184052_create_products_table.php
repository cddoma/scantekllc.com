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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('stripe_id', 32)->nullable()->default(null);
            $table->tinyText('name');
            $table->text('description')->nullable()->default(null);
            $table->text('notes')->nullable()->default(null);
            $table->boolean('hidden')->default(false);
            $table->boolean('active')->default(true);
            $table->float('default_price', 8, 2)->nullable()->default(null);
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
        Schema::dropIfExists('products');
    }
};
