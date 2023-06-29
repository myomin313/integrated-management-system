<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNsIncomeTaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ns_income_taxes', function (Blueprint $table) {
            $table->id();
            $table->date("date");
            $table->integer("salary_id");
            $table->integer("user_id");
            $table->double("salary_usd",10,2);
            $table->double("ot_usd",10,2)->default(0);
            $table->double("bonus_usd",10,2)->default(0);
            $table->double("leave_usd",10,2)->default(0);
            $table->double("adjustment_usd",10,2)->default(0);
            $table->double("total_income_usd",10,2)->default(0);
            $table->string("estimated_type",20)->nullable();
            $table->float("estimated_percent")->default(0);
            $table->float("estimated_usd")->default(0);
            $table->double("estimated_income_tax",10,2)->default(0);
            $table->float("estimated_income_tax_round",10)->default(0);
            $table->double("exchange_rate",10,2)->default(0);
            $table->text("remark")->nullable();
            $table->integer("basic_allowance_percent")->default(0);
            $table->double("basic_max_allowance",15,2)->default(0);
            $table->double("parent_allowance",15,2)->default(0);
            $table->double("spouse_allowance",15,2)->default(0);
            $table->double("children_allowance",15,2)->default(0);
            $table->double("life_assured",15,2)->default(0);
            $table->double("one_year_tax",15,2)->default(0);
            $table->double("one_month_tax",15,2)->default(0);
            $table->double("deducted_tax_rate",15,2)->default(0);
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
        Schema::dropIfExists('ns_income_taxes');
    }
}
