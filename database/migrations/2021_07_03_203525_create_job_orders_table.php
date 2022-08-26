<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_orders', function (Blueprint $table) {
            $table->id();

            $table->string('order_no_manual')->nullable();
            
            // Same or different for each product based on Null check
            $table->string('po_no')->nullable();
            $table->date('qc_date')->nullable();
            $table->date('crd_date')->nullable();
            $table->string('container_vol')->nullable();

            $table->smallInteger('combine_models_bom')->default(0)->nullable();

            $table->unsignedBigInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers');

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
        Schema::dropIfExists('job_orders');
    }
}
