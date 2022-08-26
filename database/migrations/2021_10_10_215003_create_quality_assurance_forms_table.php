<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQualityAssuranceFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quality_assurance_forms', function (Blueprint $table) {
            $table->id();

            $table->string('form_name');
            $table->string('description');
            $table->smallInteger('qa_type')->comment('1=IQC 2=IPQC 3=FQC');
            $table->smallInteger('qa_category')->nullable()->comment('1=RAW MATERIAL 2=HARDWARE 3=POLYFORM 4=CARTON');

            $table->string('guide_std_file')->nullable();
            
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
        Schema::dropIfExists('quality_assurance_forms');
    }
}
