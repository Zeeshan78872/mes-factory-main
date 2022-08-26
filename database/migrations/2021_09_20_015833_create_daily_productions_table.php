<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyProductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_productions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('department_id')->nullable();
            $table->foreign('department_id')->references('id')->on('departments');

            $table->smallInteger('work_status')->default(1)->nullable()->comment('1=new 2=rework');

            $table->unsignedInteger('total_quantity_plan')->nullable();
            $table->unsignedInteger('total_quantity_produced')->nullable();
            $table->unsignedInteger('total_quantity_rejected')->nullable();
            $table->string('testing_speed')->nullable();

            $table->smallInteger('is_ended')->default(0)->nullable()->comment('1=yes 0=no');

            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');

            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users');

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
        Schema::dropIfExists('daily_productions');
    }
}
