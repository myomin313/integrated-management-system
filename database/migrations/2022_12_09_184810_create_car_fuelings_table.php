<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarFuelingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_fuelings', function (Blueprint $table) {
            $table->id();
            $table->integer('car_id');
            $table->date('date');
            $table->float('liter');
            $table->float('current_meter');
            $table->float('mileage_difference');
            $table->integer('rate');
            $table->integer('driver');
            $table->integer('totalRate');
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('car_fuelings');
    }
}
