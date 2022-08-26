<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingProgressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_progresses', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('shipping_item_id');
            $table->foreign('shipping_item_id')->references('id')->on('shipping_items');

            $table->smallInteger('timer_type')->default(1)->nullable()->comment('1=loading 2=break');

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
        Schema::dropIfExists('shipping_progresses');
    }
}
