<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChangeRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('change_requests', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->date('actual_date');
            $table->date('changing_date');
            $table->time('changing_start_time')->nullable();
            $table->time('changing_end_time')->nullable();
            $table->time('working_start_time')->nullable();
            $table->time('working_end_time')->nullable();
            $table->datetime('requested_date');
            $table->datetime('status_change_date');
            $table->tinyInteger('status')->default(0);
            $table->integer('status_change_by');
            $table->text('reason_of_correction')->nullable();
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
        Schema::dropIfExists('change_requests');
    }
}
