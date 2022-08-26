<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyProductionStockCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_production_stock_cards', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('daily_production_id');
            $table->foreign('daily_production_id')->references('id')->on('daily_productions');

            $table->unsignedBigInteger('stock_card_id');
            $table->foreign('stock_card_id')->references('id')->on('product_stock_cards');

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
        Schema::dropIfExists('daily_production_stock_cards');
    }
}
