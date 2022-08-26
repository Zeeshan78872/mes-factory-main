<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductStockCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_stock_cards', function (Blueprint $table) {
            $table->id();

            $table->string('stock_card_number')->nullable();
            $table->unsignedInteger('ordered_quantity')->nullable();
            $table->unsignedInteger('available_quantity')->nullable();

            $table->date('date_in');
            $table->date('date_out')->nullable();

            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')->references('id')->on('job_orders')->nullable();

            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products');

            $table->unsignedBigInteger('job_product_id');
            $table->foreign('job_product_id')->references('id')->on('products')->nullable();

            $table->unsignedSmallInteger('is_rejected')->default(0)->nullable();
            $table->unsignedSmallInteger('is_balance')->default(0)->nullable();

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
        Schema::dropIfExists('product_stock_cards');
    }
}
