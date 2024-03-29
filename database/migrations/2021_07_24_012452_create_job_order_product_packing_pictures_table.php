<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobOrderProductPackingPicturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_order_product_packing_pictures', function (Blueprint $table) {
            $table->id();
            $table->string('picture_link');
            $table->unsignedBigInteger('job_order_product_id');
            $table->foreign('job_order_product_id')->references('id')->on('job_order_products')->onDelete('cascade');
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
        Schema::dropIfExists('job_order_product_packing_pictures');
    }
}
