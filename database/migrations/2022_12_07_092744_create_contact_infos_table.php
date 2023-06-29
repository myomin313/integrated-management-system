<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_infos', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('first_person_name',100);
            $table->string('first_person_email',100)->nullable();
            $table->string('first_person_phone',50)->nullable();
            $table->string('first_person_hotline',50)->nullable();
            $table->string('first_person_relationship',50);
            $table->text('first_person_address')->nullable();
            $table->string('second_person_name',100)->nullable();
            $table->string('second_person_email',100)->nullable();
            $table->string('second_person_phone',50)->nullable();
            $table->string('second_person_hotline',50)->nullable();
            $table->string('second_person_relationship',50)->nullable();
            $table->text('second_person_address')->nullable();
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
        Schema::dropIfExists('contact_infos');
    }
}
