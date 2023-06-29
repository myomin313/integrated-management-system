<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRawAttLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('raw_att_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('att_id');
            $table->integer('att_UserID');
            $table->string('att_ip');
            $table->string('att_serial');
            $table->datetime('att_Date');
            $table->integer('branch')->default(0);
            $table->text('reason')->nullable();
            $table->datetime('created_date');
            $table->integer('updated_by');
            $table->string('ip');
            $table->string('method');
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
        Schema::dropIfExists('raw_att_logs');
    }
}
