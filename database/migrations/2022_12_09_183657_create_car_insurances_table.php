<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarInsurancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_insurances', function (Blueprint $table) {
            $table->id();
            $table->string('insurance_no');
            $table->integer('car_id');
            $table->string('insurance_company',100);
            $table->float('premium_amount');
            $table->string('currency',20);
            $table->date('start_date');
            $table->date('end_date');
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
        Schema::dropIfExists('car_insurances');
    }
}
