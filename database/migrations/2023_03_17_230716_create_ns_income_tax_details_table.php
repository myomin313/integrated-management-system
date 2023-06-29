<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNsIncomeTaxDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ns_income_tax_details', function (Blueprint $table) {
            $table->id();
            $table->integer('ns_income_tax_id');
            $table->integer('user_id');
            $table->integer('year');
            $table->string('month',25);
            $table->double('salary_usd',15,2)->default(0);
            $table->double('ot_usd',15,2)->default(0);
            $table->double('ssc_usd',15,2)->default(0);
            $table->double('bonus_usd',15,2)->default(0);
            $table->double('total_salary_usd',15,2)->default(0);
            $table->double('exchange_rate',15,2)->default(0);
            $table->double('total_salary_mmk',15,2)->default(0);
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
        Schema::dropIfExists('ns_income_tax_details');
    }
}
