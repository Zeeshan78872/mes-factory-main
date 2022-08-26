<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobOrderProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_order_products', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')->references('id')->on('job_orders');

            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products');

            $table->unsignedInteger('quantity');
            
            // if different for each product
            $table->string('po_no')->nullable();
            $table->date('qc_date')->nullable();
            $table->date('crd_date')->nullable();
            $table->string('container_vol')->nullable();

            $table->smallInteger('product_test')->default(0)->nullable();
            $table->string('product_test_remarks')->nullable();
            $table->string('remarks')->nullable();
            $table->mediumText('product_packing')->nullable();

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
        Schema::dropIfExists('job_order_products');
    }
}
