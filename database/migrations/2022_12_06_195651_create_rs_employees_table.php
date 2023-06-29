<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRsEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rs_employees', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('second_bank_name_mmk')->nullable();
            $table->string('second_bank_account_mmk',50)->nullable();
            $table->text('final_education')->nullable();
            $table->text('residant_place')->nullable();
            $table->string('form_c')->nullable();
            $table->string('frc_no',50)->nullable();
            $table->string('mjsrv')->nullable();
            $table->date('mjsrv_expire_date')->nullable();
            $table->string('stay_permit')->nullable();
            $table->date('stay_permit_expire_date')->nullable();
            $table->date('aboard_date')->nullable();
            $table->string('japan_hot_line')->nullable();
            $table->text('japan_address')->nullable();
            $table->string('japan_phone')->nullable();
            $table->text('myanmar_address')->nullable();
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
        Schema::dropIfExists('rs_employees');
    }
}
