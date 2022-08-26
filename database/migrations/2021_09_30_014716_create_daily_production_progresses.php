<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyProductionProgresses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_production_progresses', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('daily_production_id');
            $table->foreign('daily_production_id')->references('id')->on('daily_productions');

            $table->smallInteger('timer_type')->default(1)->nullable()->comment('1=production 2=break');

            $table->dateTime('started_at');
            $table->dateTime('ended_at')->nullable();

            $table->bigInteger('difference_seconds')->nullable();

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
        Schema::dropIfExists('daily_production_progresses');
    }
}
