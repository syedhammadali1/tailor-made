<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableRequestMeasurementAddNewColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('request_measurements', function (Blueprint $table) {
            $table->string('neck_circumference1')->nullable();
            $table->string('shoulder_to_shoulder2')->nullable();
            $table->string('chest_circumference3')->nullable();
            $table->string('circumference_under_the_breast3a')->nullable();
            $table->string('waist_circumference4')->nullable();
            $table->string('back_length5')->nullable();
            $table->string('shoulder_to_wrist6')->nullable();
            $table->string('shoulder_to_elbow7')->nullable();
            $table->string('wrist_to_elbow8')->nullable();
            $table->string('biceps9')->nullable();
            $table->string('forearm10')->nullable();
            $table->string('wrist_circumference11')->nullable();
            $table->string('waist_to_ankle_length12')->nullable();
            $table->string('hip_circumference13')->nullable();
            $table->string('thigh_circumference14')->nullable();
            $table->string('circumference_of_knees15')->nullable();
            $table->string('calf_circumference16')->nullable();
            $table->string('crotch_to_ankle17')->nullable();
            $table->string('knees_to_ankle18')->nullable();
            $table->string('ankle_circumference19')->nullable();
            $table->string('neck_to_ankle20')->nullable();
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
