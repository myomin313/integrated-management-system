<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRsIncomeTaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rs_income_taxes', function (Blueprint $table) {
            $table->id();
            $table->date("date");
            $table->integer("salary_id");
            $table->integer("user_id");
            $table->double("salary",10,2)->default(0);
            $table->double("income_tax_usd",10,2)->default(0);
            $table->double("exchange_rate",10,2)->default(0);
            $table->double("income_tax_mmk",10,2)->default(0);
            $table->double("ssc",10,2)->default(0);
            $table->double("percent_allowance",10,2)->default(0);
            $table->double("max_allowance",10,2)->default(0);
            $table->double("parent_allowance",10,2)->default(0);
            $table->double("spouse_allowance",10,2)->default(0);
            $table->double("children_allowance",10,2)->default(0);
            $table->double("tax_calculation_percent",10,2)->default(0);
            $table->double("one_year_tax",10,2)->default(0);
            $table->text("remark")->nullable();
            $table->float("actual_tax_mmk",12,2)->default(0);
            $table->float("actual_exchange_rate",12,2)->default(0);
            $table->float("actual_tax_usd",10,2)->default(0);
            $table->float("actual_rate",10,2)->default(0);
            $table->integer("updated_by")->nullable();
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
        Schema::dropIfExists('rs_income_taxes');
    }
}
