<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobOrderBomListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_order_bom_lists', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')->references('id')->on('job_orders');
            
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products');

            $table->unsignedBigInteger('item_id');
            $table->foreign('item_id')->references('id')->on('products');

            $table->unsignedInteger('quantity');
            $table->unsignedInteger('total_quantity');

            $table->unsignedSmallInteger('order_size_same_as_bom')->nullable()->default(0);

            $table->string('code_generated')->nullable();
            $table->unsignedSmallInteger('status')->nullable();
            $table->string('remarks')->nullable();
            
            $table->string('length')->nullable();
            $table->string('length_unit')->nullable();
            $table->string('width')->nullable();
            $table->string('width_unit')->nullable();
            $table->string('height')->nullable();
            $table->string('height_unit')->nullable();
            $table->string('thick')->nullable();
            $table->string('thick_unit')->nullable();

            $table->string('location_receiving')->nullable();
            $table->string('location_produce')->nullable();
            $table->string('location_loading')->nullable();

            $table->unsignedInteger('order_quantity')->nullable();
            $table->string('order_length')->nullable();
            $table->string('order_length_unit')->nullable();
            $table->string('order_width')->nullable();
            $table->string('order_width_unit')->nullable();
            $table->string('order_height')->nullable();
            $table->string('order_height_unit')->nullable();
            $table->string('order_thick')->nullable();
            $table->string('order_thick_unit')->nullable();

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
        Schema::dropIfExists('job_order_bom_lists');
    }
}
