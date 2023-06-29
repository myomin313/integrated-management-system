<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnnualOtSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('annual_ot_summaries', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('branch');
            $table->float('april')->default(0);
            $table->float('may')->default(0);
            $table->float('june')->default(0);
            $table->float('july')->default(0);
            $table->float('auguest')->default(0);
            $table->float('september')->default(0);
            $table->float('october')->default(0);
            $table->float('november')->default(0);
            $table->float('december')->default(0);
            $table->float('january')->default(0);
            $table->float('february')->default(0);
            $table->float('march')->default(0);
            $table->integer('year');
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
        Schema::dropIfExists('annual_ot_summaries');
    }
}
