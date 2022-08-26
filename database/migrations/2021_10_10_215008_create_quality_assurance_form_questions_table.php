<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQualityAssuranceFormQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quality_assurance_form_questions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('qa_form_id');
            $table->foreign('qa_form_id')->references('id')->on('quality_assurance_forms');

            $table->string('defect_category', 150)->nullable();
            $table->string('question', 255);
            $table->smallInteger('is_remarks')->default(0)->nullable()->comment('0=no 1=yes');

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
        Schema::dropIfExists('quality_assurance_form_questions');
    }
}
