<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePremiumAmountUpdatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('premium_amount_updates', function (Blueprint $table) {
            $table->id();
            $table->integer('car_insurance_id');
            $table->integer('car_id');
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
        Schema::dropIfExists('premium_amount_updates');
    }
}
