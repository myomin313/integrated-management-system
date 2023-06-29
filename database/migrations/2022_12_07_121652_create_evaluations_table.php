<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvaluationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('year');
            $table->string('grade',10);
            $table->string('title');
            $table->integer('branch_id');
            $table->integer('position_id');
            $table->integer('department_id');
            $table->string('competency')->nullable();
            $table->string('performance',10);
            $table->float('net_pay')->nullable();
            $table->float('basic_salary')->nullable();
            $table->float('allowance')->nullable();
            $table->float('ot_rate')->nullable();
            $table->float('water_festival_bonus')->nullable();
            $table->float('thadingyut_bonus')->nullable();
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
        Schema::dropIfExists('evaluations');
    }
}
