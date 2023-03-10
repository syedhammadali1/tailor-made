<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsAttachmentMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->boolean('is_attachment')->after('user_id')->nullable();
            $table->integer('attachment_id')->after('is_attachment')->nullable();
            $table->boolean('customer_viewed')->default(0)->after('attachment_id');
            $table->boolean('delivery_boy_viewed')->default(0)->after('customer_viewed');
            $table->boolean('seller_viewed')->default(0)->after('delivery_boy_viewed');
            $table->boolean('measurer_viewed')->default(0)->after('seller_viewed');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('messages', function (Blueprint $table) {
            //
        });
    }
}
