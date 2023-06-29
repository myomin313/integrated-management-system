<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarRepairAndMaintanancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_repair_and_maintanances', function (Blueprint $table) {
            $table->id();
            $table->integer('car_id');
            $table->float('kilo_meter');
            $table->date('repair_date');
            $table->float('amount');
            $table->string('repair_type');
            $table->text('repair_detail')->nullable();
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
        Schema::dropIfExists('car_repair_and_maintanances');
    }
}
