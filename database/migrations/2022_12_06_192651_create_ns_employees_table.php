<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNsEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ns_employees', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->text('nrc')->nullable();
            $table->string('religion',50)->nullable();
            $table->string('race',50)->nullable();
            $table->text('current_address')->nullable();
            $table->text('new_address')->nullable();
            $table->string('new_phone',50)->nullable();
            $table->text('others_address')->nullable();
            $table->string('others_phone',50)->nullable();
            $table->string('employment_contract_no')->nullable();
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
        Schema::dropIfExists('ns_employees');
    }
}
