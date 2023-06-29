<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSscsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sscs', function (Blueprint $table) {
            $table->id();
            $table->date("date");
            $table->integer("salary_id");
            $table->integer("user_id");
            $table->double("salary_usd",10,2);
            $table->double("salary_mmk",10,2);
            $table->integer("employer_first_percent");
            $table->integer("employee_percent");
            $table->integer("employer_second_percent");
            $table->text("remark")->nullable();
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
        Schema::dropIfExists('sscs');
    }
}
