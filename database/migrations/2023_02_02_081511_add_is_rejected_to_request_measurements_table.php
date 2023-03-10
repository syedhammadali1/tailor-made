<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsRejectedToRequestMeasurementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('request_measurements', function (Blueprint $table) {
            $table->bigInteger('is_rejected')->nullable()->after('customer_address_id');
            $table->bigInteger('rejected_by')->nullable()->after('is_rejected');
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
