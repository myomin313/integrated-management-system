<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRsIncomeTaxJpyDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rs_income_tax_jpy_details', function (Blueprint $table) {
            $table->id();
            $table->integer("rs_income_tax_id");
            $table->integer("user_id");
            $table->integer("year");
            $table->string("month",25);
            $table->double("salary_yen",15,2)->default(0);
            $table->double("transfer_from_mm_yen",15,2)->default(0);
            $table->double("adjustment_yen",15,2)->default(0);
            $table->double("income_tax_jpy_yen",15,2)->default(0);
            $table->double("bonus_yen",15,2)->default(0);
            $table->double("oversea_yen",15,2)->default(0);
            $table->double("dc_yen",15,2)->default(0);
            $table->double("total_salary_yen",15,2)->default(0);
            $table->double("exchange_rate",15,2)->default(0);
            $table->double("total_salary_mmk",15,2)->default(0);
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
        Schema::dropIfExists('rs_income_tax_jpy_details');
    }
}
