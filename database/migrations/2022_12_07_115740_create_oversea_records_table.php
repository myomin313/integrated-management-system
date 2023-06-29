<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOverseaRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oversea_records', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('country_name',50);
            $table->text('purpose')->nullable();
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
        Schema::dropIfExists('oversea_records');
    }
}
