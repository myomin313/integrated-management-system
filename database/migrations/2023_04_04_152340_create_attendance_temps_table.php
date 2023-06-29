<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendanceTempsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_temps', function (Blueprint $table) {
            $table->id();
            $table->integer("change_request_id")->default(0);
            $table->integer("user_id")->default(0);
            $table->date("date")->nullable();
            $table->time("start_time")->nullable();
            $table->time("end_time")->nullable();
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
        Schema::dropIfExists('attendance_temps');
    }
}
