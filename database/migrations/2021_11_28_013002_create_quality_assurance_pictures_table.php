<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQualityAssurancePicturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quality_assurance_pictures', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('quality_assurance_id');
            $table->foreign('quality_assurance_id')->references('id')->on('quality_assurances');

            $table->string('picture_link')->nullable();
            $table->mediumText('comments')->nullable();

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
        Schema::dropIfExists('quality_assurance_pictures');
    }
}
