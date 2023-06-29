<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarMileagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_mileages', function (Blueprint $table) {
            $table->id();
            $table->integer('car_id');
            $table->date('date');
            $table->float('current_km')->default(0);
            $table->float('km')->default(0);
            $table->float('liter')->default(0);
            $table->float('actual_km')->default(0);
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
        Schema::dropIfExists('car_mileages');
    }
}
