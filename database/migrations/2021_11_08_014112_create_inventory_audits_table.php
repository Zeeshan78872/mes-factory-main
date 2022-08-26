<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryAuditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_audits', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('stock_card_id');
            $table->foreign('stock_card_id')->references('id')->on('product_stock_cards');

            $table->smallInteger('movement_type')->comment('1=in 2=out');

            $table->unsignedInteger('quantity');
            $table->string('remarks')->nullable();

            $table->unsignedBigInteger('site_id')->nullable();
            $table->foreign('site_id')->references('id')->on('sites');

            $table->unsignedBigInteger('site_location_id')->nullable();
            $table->foreign('site_location_id')->references('id')->on('site_locations');

            $table->unsignedBigInteger('department_id')->nullable();
            $table->foreign('department_id')->references('id')->on('departments');

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
        Schema::dropIfExists('inventory_audits');
    }
}
