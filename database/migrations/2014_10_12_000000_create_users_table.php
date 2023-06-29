<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('noti_type',20)->nullable();
            $table->string('email')->nullable()->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->text('google2fa_secret')->nullable();
            $table->rememberToken();
            $table->integer('profile_id')->nullable();
            $table->string('employee_name')->nullable();
            $table->date('dob')->nullable();
            $table->date('entranced_date')->nullable();
            $table->time('working_start_time')->nullable();
            $table->time('working_end_time')->nullable();
            $table->string('personal_email')->nullable();
            $table->integer('employee_type_id')->nullable();
            $table->integer('branch_id')->nullable();
            $table->integer('department_id')->nullable();
            $table->integer('position_id')->nullable();
            $table->string('gender',20)->nullable();
            $table->string('marital_status',20)->nullable();
            $table->string('blood_type',10)->nullable();
            $table->string('ssc_no',50)->nullable();
            $table->integer('bank_name_usd')->nullable();
            $table->string('bank_account_usd',50)->nullable();
            $table->integer('bank_name_mmk')->nullable();
            $table->string('bank_account_mmk',50)->nullable();
            $table->text('passport_no',50)->nullable();
            $table->date('date_of_issue')->nullable();
            $table->date('date_of_expire')->nullable();
            $table->tinyInteger('active')->default(1);
            $table->tinyInteger('retire')->default(0);
            $table->string('photo_name')->nullable();
            $table->string('photo_url')->nullable();
            $table->string('sign_photo_name')->nullable();
            $table->string('sign_photo_url')->nullable();
            $table->text('other_changing_condition')->nullable();
            $table->tinyInteger('check_ns_rs')->default(0);
            $table->tinyInteger('password_change')->default(0);
            $table->tinyInteger('profile_add')->default(0);
            $table->integer('working_day_per_week')->default(0);
            $table->string('phone',50)->nullable();
            $table->string('working_day_type',20)->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
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
        Schema::dropIfExists('users');
    }
}
