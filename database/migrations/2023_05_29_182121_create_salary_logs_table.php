<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalaryLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salary_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('salary_id');
            $table->integer('user_id');
            $table->string('pay_for',20);
            $table->integer('year');
            $table->integer('month');
            $table->date('leave_from_date')->nullable();
            $table->date('leave_to_date')->nullable();
            $table->string('employee_type',20)->nullable();
            $table->float('exchange_rate_usd');
            $table->float('exchange_rate_yen');
            $table->float('payment_exchange_rate');
            $table->string('previous_normal_ot_hr')->default(0);
            $table->string('previous_sat_ot_hr')->default(0);
            $table->string('previous_sunday_ot_hr')->default(0);
            $table->string('previous_public_ot_hr')->default(0);
            $table->string('current_normal_ot_hr')->default(0);
            $table->string('current_sat_ot_hr')->default(0);
            $table->string('current_sunday_ot_hr')->default(0);
            $table->string('current_public_ot_hr')->default(0);
            $table->string('total_ot_hr')->default(0);
            $table->string('total_working_hour')->default(0);
            $table->float('hourly_rate')->default(0);
            $table->float('salary_usd',20,2)->default(0);
            $table->float('salary_mmk',20,2)->default(0);
            $table->float('kbz_opening_usd',20,2)->default(0);
            $table->float('kbz_opening_mmk',20,2)->default(0);
            $table->float('transfer_to_japan_usd',20,2)->default(0);
            $table->float('transfer_to_japan_mmk',20,2)->default(0);
            $table->float('transfer_from_japan_usd',20,2)->default(0);
            $table->float('transfer_from_japan_mmk',20,2)->default(0);
            $table->float('electricity_usd')->default(0);
            $table->float('electricity_mmk')->default(0);
            $table->float('car_usd')->default(0);
            $table->float('car_mmk')->default(0);
            $table->tinyInteger('no_ssc')->default(0);
            $table->decimal('ssc_exchange')->default(0);
            $table->decimal('mmk_ssc')->default(0);
            $table->decimal('usd_ssc')->default(0);
            $table->float('ssc_mmk')->default(0);
            $table->float('ssc_usd')->default(0);
            $table->float('ot_rate_usd')->default(0);
            $table->float('previous_normal_ot_payment_usd',15,2)->default(0);
            $table->float('previous_sat_ot_payment_usd',15,2)->default(0);
            $table->float('previous_sunday_ot_payment_usd',15,2)->default(0);
            $table->float('previous_public_ot_payment_usd',15,2)->default(0);
            $table->float('previous_taxi_charge_usd')->default(0);
            $table->float('current_normal_ot_payment_usd',15,2)->default(0);
            $table->float('current_sat_ot_payment_usd',15,2)->default(0);
            $table->float('current_sunday_ot_payment_usd',15,2)->default(0);
            $table->float('current_public_ot_payment_usd',15,2)->default(0);
            $table->float('current_taxi_charge_usd')->default(0);
            $table->float('total_ot_payment_usd',15,2)->default(0);
            $table->float('total_ot_payment_mmk',15,2)->default(0);
            $table->float('unpaid_leave_day')->default(0);
            $table->float('daily_rate_usd')->default(0);
            $table->float('leave_amount_usd')->default(0);
            $table->float('leave_amount_mmk')->default(0);
            $table->float('other_adjustment_usd',15,2)->default(0);
            $table->float('other_adjustment_mmk',15,2)->default(0);
            $table->string('estimated_type',20)->nullable();
            $table->float('estimated_percent')->default(0);
            $table->float('estimated_tax_usd',15,2)->default(0);
            $table->float('estimated_tax_mmk',15,2)->default(0);
            $table->float('actual_percent')->default(0);
            $table->float('actual_tax_usd',15,2)->default(0);
            $table->float('actual_tax_mmk',15,2)->default(0);
            $table->float('usd_allowance_usd',15,2)->default(0);
            $table->float('usd_allowance_mmk',15,2)->default(0);
            $table->float('mmk_allowance_usd',15,2)->default(0);
            $table->float('mmk_allowance_mmk',15,2)->default(0);
            $table->float('usd_deduction_usd',15,2)->default(0);
            $table->float('usd_deduction_mmk',15,2)->default(0);
            $table->float('mmk_deduction_usd',15,2)->default(0);
            $table->float('mmk_deduction_mmk',15,2)->default(0);
            $table->string('bonus_name',20,2)->nullable();
            $table->text('other_bonus')->nullable();
            $table->float('bonus_usd',20,2)->default(0);
            $table->float('bonus_mmk',20,2)->default(0);
            $table->tinyInteger('is_retire')->default(0);
            $table->float('retire_fee',15,2)->default(0);
            $table->float('net_salary_usd',20,2)->default(0);
            $table->float('net_salary_mmk',20,2)->default(0);
            $table->float('transfer_mmk_acc',20,2)->default(0);
            $table->float('transfer_usd_acc',20,2)->default(0);
            $table->float('transfer_mmk_cash',20,2)->default(0);
            $table->float('transfer_usd_cash',20,2)->default(0);
            $table->text('pay_slip_remark')->nullable();
            $table->text('ssc_remark')->nullable();
            $table->text('monthly_paye_remark')->nullable();
            $table->date('pay_date');
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('salary_logs');
    }
}
