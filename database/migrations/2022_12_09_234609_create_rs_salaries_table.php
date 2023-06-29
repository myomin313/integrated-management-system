<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRsSalariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rs_salaries', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('salary_type',20);
            $table->float('april',15,2);
            $table->float('may',15,2);
            $table->float('june',15,2);
            $table->float('july',15,2);
            $table->float('auguest',15,2);
            $table->float('september',15,2);
            $table->float('october',15,2);
            $table->float('november',15,2);
            $table->float('december',15,2);
            $table->float('january',15,2);
            $table->float('february',15,2);
            $table->float('march',15,2);
            $table->integer('year');
            $table->date('date');
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
        Schema::dropIfExists('rs_salaries');
    }
}
