<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQualityAssuranceAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quality_assurance_answers', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('quality_assurance_id');
            $table->foreign('quality_assurance_id')->references('id')->on('quality_assurances');

            $table->unsignedBigInteger('qa_form_question_id');
            $table->foreign('qa_form_question_id')->references('id')->on('quality_assurance_form_questions');

            $table->smallInteger('answer')->comment('1=accepted 2=rejected 3=reworks 4=scrap 5=subcon');
            $table->string('remarks')->nullable();
            $table->unsignedDecimal('cr', 4, 2)->nullable();
            $table->unsignedDecimal('mi', 4, 2)->nullable();
            $table->unsignedDecimal('mn', 4, 2)->nullable();

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
        Schema::dropIfExists('quality_assurance_answers');
    }
}
