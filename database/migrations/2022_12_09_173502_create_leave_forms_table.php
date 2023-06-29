<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_forms', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('leave_type_id');
            $table->date('from_date');
            $table->date('to_date');
            $table->string('day_type',10);
            $table->string('time',10)->nullable();
            $table->text('reason')->nullable();
            $table->integer('approve_by_dep_manager')->nullable();
            $table->integer('approve_by_GM')->nullable();
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
        Schema::dropIfExists('leave_forms');
    }
}
