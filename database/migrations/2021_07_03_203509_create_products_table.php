<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on('products');

            $table->unsignedDecimal('price_per_unit', 10, 4)->nullable();

            $table->string('model_name');
            $table->string('product_name')->nullable();
            $table->string('material')->nullable();
            $table->string('color_name')->nullable();
            $table->string('color_code')->nullable();
            $table->string('length')->nullable();
            $table->string('length_unit')->nullable();
            $table->string('width')->nullable();
            $table->string('width_unit')->nullable();
            $table->string('height')->nullable();
            $table->string('height_unit')->nullable();
            $table->string('thick')->nullable();
            $table->string('thick_unit')->nullable();
            $table->text('item_description')->nullable();
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
        Schema::dropIfExists('products');
    }
}
