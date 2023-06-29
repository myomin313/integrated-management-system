<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyOtRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_ot_requests', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('branch');
            $table->string('ot_type');
            $table->date('apply_date');
            $table->time('start_from_time');
            $table->time('start_to_time');
            $table->integer('start_break_hour')->default(0);
            $table->integer('start_break_minute')->default(0);
            $table->text('start_reason')->nullable();
            $table->tinyInteger('start_hotel')->default(0);
            $table->tinyInteger('start_next_day')->default(0);
            $table->time('end_from_time')->nullable();
            $table->time('end_to_time')->nullable();
            $table->integer('end_break_hour')->default(0);
            $table->integer('end_break_minute')->default(0);
            $table->text('end_reason')->nullable();
            $table->tinyInteger('end_hotel')->default(0);
            $table->tinyInteger('end_next_day')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->text('status_reason')->nullable();
            $table->text('monthly_status_reason')->nullable();
            $table->datetime('status_change_date')->nullable();
            $table->integer('status_change_by')->default(0);
            $table->tinyInteger('monthly_request')->default(0);
            $table->integer('monthly_request_id')->default(0);
            $table->tinyInteger('inactive')->default(0);
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
        Schema::dropIfExists('daily_ot_requests');
    }
}
