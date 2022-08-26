<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQualityAssurancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quality_assurances', function (Blueprint $table) {
            $table->id();

            $table->smallInteger('qa_type')->comment('1=IQC 2=IPQC 3=FQC');
            $table->smallInteger('qa_category')->nullable()->comment('1=RAW MATERIAL 2=HARDWARE 3=POLYFORM 4=CARTON');

            $table->unsignedBigInteger('department_id')->nullable();
            $table->foreign('department_id')->references('id')->on('departments');

            $table->unsignedBigInteger('stock_card_id');
            $table->foreign('stock_card_id')->references('id')->on('product_stock_cards');

            $table->unsignedBigInteger('qa_by');
            $table->foreign('qa_by')->references('id')->on('users');

            $table->integer('total_quantity')->nullable();
            $table->integer('sample_size')->nullable();

            $table->unsignedDecimal('total_defects_found_cr', 4, 2)->nullable();
            $table->unsignedDecimal('total_defects_found_mj', 4, 2)->nullable();
            $table->unsignedDecimal('total_defects_found_mn', 4, 2)->nullable();
            $table->unsignedDecimal('total_defects_allowed_cr', 4, 2)->nullable();
            $table->unsignedDecimal('total_defects_allowed_mj', 4, 2)->nullable();
            $table->unsignedDecimal('total_defects_allowed_mn', 4, 2)->nullable();

            $table->string('product_picture_link')->nullable();

            $table->mediumText('remarks')->nullable();
            $table->mediumText('comments')->nullable();

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
        Schema::dropIfExists('quality_assurances');
    }
}
