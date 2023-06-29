<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonthlyDriverOtRequestDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monthly_driver_ot_request_details', function (Blueprint $table) {
            $table->id();
            $table->integer('monthly_ot_request_id');
            $table->integer('attendance_id');
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
            $table->tinyInteger('morning_taxi_time')->default(0);
            $table->tinyInteger('evening_taxi_time')->default(0);
            $table->tinyInteger('manager_status')->default(0);
            $table->tinyInteger('account_status')->default(0);
            $table->tinyInteger('gm_status')->default(0);
            $table->text('manager_status_reason')->nullable();
            $table->text('account_status_reason')->nullable();
            $table->text('gm_status_reason')->nullable();
            $table->datetime('manager_change_date')->nullable();
            $table->integer('manager_change_by')->default(0);
            $table->datetime('account_change_date')->nullable();
            $table->integer('account_change_by')->default(0);
            $table->datetime('gm_change_date')->nullable();
            $table->integer('gm_change_by')->default(0);
            $table->tinyInteger('attendance')->default(0);
            $table->string('day_type',20)->nullable();
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
        Schema::dropIfExists('monthly_driver_ot_request_details');
    }
}
