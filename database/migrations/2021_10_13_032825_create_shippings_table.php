<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shippings', function (Blueprint $table) {
            $table->id();

            $table->smallInteger('load_to')->comment('1=contena 2=lorry');

            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')->references('id')->on('job_orders');

            $table->date('truck_out_date')->nullable();
            $table->date('qc_date')->nullable();
            $table->string('booking_no')->nullable();
            $table->string('container_no')->nullable();
            $table->string('seal_no')->nullable();
            $table->string('vehicle_no')->nullable();
            $table->string('company')->nullable();
            $table->string('do_no')->nullable();
            $table->string('description')->nullable();
            
            $table->smallInteger('is_ended')->default(0)->nullable()->comment('1=yes 0=no');

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
        Schema::dropIfExists('shippings');
    }
}
