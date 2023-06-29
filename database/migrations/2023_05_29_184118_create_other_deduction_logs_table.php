<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOtherDeductionLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('other_deduction_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('salary_lod_id');
            $table->integer('salary_id');
            $table->text('name');
            $table->float('amount',15,2);
            $table->string('currency',10);
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
        Schema::dropIfExists('other_deduction_logs');
    }
}
