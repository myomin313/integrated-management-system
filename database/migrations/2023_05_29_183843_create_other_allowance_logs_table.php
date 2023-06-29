<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOtherAllowanceLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('other_allowance_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('salary_log_id');
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
        Schema::dropIfExists('other_allowance_logs');
    }
}