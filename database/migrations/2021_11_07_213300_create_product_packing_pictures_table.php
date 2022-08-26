<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductPackingPicturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_packing_pictures', function (Blueprint $table) {
            $table->id();

            $table->string('picture_link');

            $table->unsignedBigInteger('product_packing_id');
            $table->foreign('product_packing_id')->references('id')->on('product_packings');

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
        Schema::dropIfExists('product_packing_pictures');
    }
}
