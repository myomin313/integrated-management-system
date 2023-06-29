<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->string('device',50);
            $table->string('device_ip',20);
            $table->string('device_serial',20)->nullable();
            $table->integer('user_id');
            $table->integer('profile_id')->default(0);
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time')->nullable();
            $table->string('type',20);
            $table->integer('type_id');
            $table->integer('branch_id');
            $table->text('remark')->nullable();
            $table->text('manual_remark')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->string('latitude',100)->nullable();
            $table->string('longitude',100)->nullable();
            $table->time('corrected_start_time');
            $table->time('corrected_end_time');
            $table->float('normal_ot_hr')->default(0);
            $table->float('sat_ot_hr')->default(0);
            $table->float('sunday_ot_hr')->default(0);
            $table->float('public_holiday_ot_hr')->default(0);
            $table->datetime('change_request_date')->nullable();
            $table->datetime('change_approve_date')->nullable();
            $table->integer('change_approve_by')->nullable();
            $table->datetime('ot_request_date')->nullable();
            $table->datetime('ot_approve_date')->nullable();
            $table->integer('ot_approve_by')->nullable();
            $table->tinyInteger('hotel')->default(0);
            $table->tinyInteger('next_day')->default(0);
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->tinyInteger('monthly_request')->default(0);
            $table->integer('monthly_request_id')->default(0);
            $table->tinyInteger('morning_ot')->default(0);
            $table->tinyInteger('evening_ot')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendances');
    }
}
