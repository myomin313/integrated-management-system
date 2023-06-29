<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRsIncomeTaxMmDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rs_income_tax_mm_details', function (Blueprint $table) {
            $table->id();
            $table->integer("rs_income_tax_id");
            $table->integer("user_id");
            $table->integer("year");
            $table->string("month",25);
            $table->double("salary_usd",10,2)->default(0);
            $table->double("transfer_to_jp_usd",10,2)->default(0);
            $table->double("bonus_usd",10,2)->default(0);
            $table->double("oversea_usd",10,2)->default(0);
            $table->double("dc_usd",10,2)->default(0);
            $table->double("total_salary_usd",10,2)->default(0);
            $table->double("exchange_rate",10,2)->default(0);
            $table->double("total_salary_mmk",10,2)->default(0);
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
        Schema::dropIfExists('rs_income_tax_mm_details');
    }
}
