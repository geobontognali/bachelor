<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_notification', function (Blueprint $table) {
            $table->increments('not_id');

            $table->integer('not_resident')->unsigned();
            $table->foreign('not_resident')->references('res_id')->on('tbl_resident');

            $table->integer('not_door')->unsigned();
            $table->foreign('not_door')->references('door_id')->on('tbl_door');

            $table->string('not_time', 45);
            $table->string('not_img', 255);
            $table->string('not_notificationcol', 45);

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
        Schema::dropIfExists('tbl_notification');
    }
}
