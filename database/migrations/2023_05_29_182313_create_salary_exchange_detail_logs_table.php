<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalaryExchangeDetailLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salary_exchange_detail_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('salary_log_id');
            $table->integer('salary_id');
            $table->string('type',20);
            $table->string('from_to',20);
            $table->float('exchange_rate',15,2);
            $table->float('usd_amount',15,2);
            $table->float('mmk_amount',15,2);
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
        Schema::dropIfExists('salary_exchange_detail_logs');
    }
}
