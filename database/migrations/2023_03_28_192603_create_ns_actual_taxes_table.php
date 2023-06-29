<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNsActualTaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ns_actual_taxes', function (Blueprint $table) {
            $table->id();
            $table->integer("user_id");
            $table->string("tax_for");
            $table->date("tax_period")->nullable();
            $table->float("tax_amount_mmk");
            $table->float("exchange_rate")->default(0);
            $table->float("tax_amount_usd");
            $table->date("pay_date");
            $table->integer("created_by");
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
        Schema::dropIfExists('ns_actual_taxes');
    }
}
