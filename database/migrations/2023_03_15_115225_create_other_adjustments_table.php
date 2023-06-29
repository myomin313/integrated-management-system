<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOtherAdjustmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('other_adjustments', function (Blueprint $table) {
            $table->id();
            $table->integer('salary_id');
            $table->date('date')->nullable();
            $table->string('type')->nullable();
            $table->text('name');
            $table->float('exchange_rate',15,2);
            $table->float('usd_amount',15,2);
            $table->float('mmk_amount',20,2);

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
        Schema::dropIfExists('other_adjustments');
    }
}
