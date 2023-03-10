<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableRequestMeasurementAddShoesNewColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('request_measurements', function (Blueprint $table) {
            $table->string('foot_a')->nullable();
            $table->string('foot_b')->nullable();
            $table->string('foot_c')->nullable();
            $table->string('foot_d')->nullable();
            $table->string('foot_e')->nullable();
            $table->string('foot_f')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('request_measurements', function (Blueprint $table) {
            //
        });
    }
}
