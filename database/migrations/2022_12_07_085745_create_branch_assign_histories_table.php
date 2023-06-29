<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchAssignHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branch_assign_histories', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->datetime('start_datetime');
            $table->datetime('end_datetime')->nullable();
            $table->integer('branch_id');
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
        Schema::dropIfExists('branch_assign_histories');
    }
}
