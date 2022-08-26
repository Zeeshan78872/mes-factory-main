<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobOrderPurchaseListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_order_purchase_lists', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')->references('id')->on('job_orders');
            
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products');

            $table->unsignedBigInteger('item_id');
            $table->foreign('item_id')->references('id')->on('products');

            // Item Details
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

            $table->unsignedInteger('bom_order_quantity')->nullable();
            $table->string('order_length')->nullable();
            $table->string('order_length_unit')->nullable();
            $table->string('order_width')->nullable();
            $table->string('order_width_unit')->nullable();
            $table->string('order_height')->nullable();
            $table->string('order_height_unit')->nullable();
            $table->string('order_thick')->nullable();
            $table->string('order_thick_unit')->nullable();

            $table->unsignedInteger('bom_quantity');
            $table->unsignedInteger('bom_total_quantity');

            // If Old Stock Order
            $table->unsignedBigInteger('stock_card_id')->nullable();
            $table->foreign('stock_card_id')->references('id')->on('product_stock_cards');
            $table->unsignedInteger('stock_card_balance_quantity')->nullable();

            // If New Stock Order
            $table->date('order_date')->nullable();
            $table->string('po_no')->nullable();
            $table->unsignedInteger('order_quantity')->nullable();
            $table->unsignedDecimal('item_price_per_unit', 10, 4)->nullable();
            $table->string('supplier_name')->nullable();
            $table->date('est_delievery_date')->nullable();
            $table->string('purchase_remarks')->nullable();

            $table->unsignedSmallInteger('send_to_subcon')->default(0)->nullable();

            // Sub Con Fields
            $table->date('subcon_date_out')->nullable();
            $table->string('subcon_do_no')->nullable();
            $table->unsignedInteger('subcon_quantity')->nullable();
            $table->unsignedDecimal('subcon_item_price_per_unit', 10, 4)->nullable();
            $table->string('subcon_name')->nullable();
            $table->date('subcon_est_delievery_date')->nullable();
            $table->string('subcon_description')->nullable();
            $table->string('subcon_remarks')->nullable();

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
        Schema::dropIfExists('job_order_purchase_lists');
    }
}
