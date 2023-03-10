<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeasurerAvailablityHoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('measurer_availablity_hours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('measurer_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('days')->nullable();
            $table->time('from_time')->nullable();
            $table->time('to_time')->nullable();
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
        Schema::dropIfExists('measurer_availablity_hours');
    }
}
