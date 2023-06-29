<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmploymentRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employment_records', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('company_name',50);
            $table->integer('branch_id');
            $table->integer('position_id');
            $table->integer('department_id');
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('employment_records');
    }
}
