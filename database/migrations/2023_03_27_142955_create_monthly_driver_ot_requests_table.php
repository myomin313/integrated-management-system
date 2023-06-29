<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonthlyDriverOtRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monthly_driver_ot_requests', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('branch');
            $table->date('date');
            $table->integer('manager_main_status')->default(0);
            $table->integer('account_main_status')->default(0);
            $table->integer('gm_main_status')->default(0);
            $table->text('manager_reason')->nullable();
            $table->text('account_reason')->nullable();
            $table->text('gm_reason')->nullable();
            $table->datetime('manager_change_date')->nullable();
            $table->datetime('account_change_date')->nullable();
            $table->datetime('gm_change_date')->nullable();
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
        Schema::dropIfExists('monthly_driver_ot_requests');
    }
}
