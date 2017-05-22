<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResidentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_resident', function (Blueprint $table) {
            $table->increments('res_id');

            $table->string('res_pw', 45);
            $table->string('res_name', 45);
            $table->string('res_secondName', 45);
            $table->string('res_displayedName', 45);
            $table->string('res_apartment', 45);

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
        Schema::dropIfExists('tbl_resident');
    }
}
