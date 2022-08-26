<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMapBomSizesColumnsInProductBomMappingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_bom_mappings', function (Blueprint $table) {
            $table->string('length')->nullable();
            $table->string('length_unit')->nullable();
            $table->string('width')->nullable();
            $table->string('width_unit')->nullable();
            $table->string('thick')->nullable();
            $table->string('thick_unit')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_bom_mappings', function (Blueprint $table) {
            $table->dropColumn('length');
            $table->dropColumn('length_unit');
            $table->dropColumn('width');
            $table->dropColumn('width_unit');
            $table->dropColumn('thick');
            $table->dropColumn('thick_unit');
        });
    }
}
