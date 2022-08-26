<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobOrderReceivingListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_order_receiving_lists', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')->references('id')->on('job_orders');

            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products');

            $table->unsignedBigInteger('purchase_id');
            $table->foreign('purchase_id')->references('id')->on('job_order_purchase_lists');

            // Receive Fields
            $table->date('date_in');
            $table->string('do_no')->nullable();

            $table->string('location_receiving')->nullable();
            $table->string('location_produce')->nullable();
            $table->string('location_loading')->nullable();

            $table->unsignedInteger('received_quantity')->nullable();
            $table->unsignedInteger('extra_less')->nullable();
            $table->unsignedInteger('balance')->nullable();
            $table->unsignedSmallInteger('balance_received_as_well')->default(0)->nullable();
            $table->string('receiving_remarks')->nullable();
            $table->unsignedSmallInteger('received_as_well')->default(0)->nullable();
            $table->string('receiving_remarks_s')->nullable();

            // Reject Fields
            $table->unsignedSmallInteger('send_to_reject')->default(0)->nullable();
            $table->date('reject_date_out')->nullable();
            $table->string('reject_memo_no')->nullable();
            $table->unsignedInteger('reject_quantity')->nullable();
            $table->date('reject_est_delievery_date')->nullable();
            $table->unsignedSmallInteger('reject_receive_as_well')->default(0)->nullable();
            $table->string('reject_cause')->nullable();
            $table->string('reject_remarks')->nullable();
            $table->string('reject_picture_link');

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
        Schema::dropIfExists('job_order_receiving_lists');
    }
}
