<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediaWheelSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media_wheel_setting', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->bigInteger('media_id')->unsigned()->unique();
			$table->foreign('media_id')->references('id')->on('media')->onDelete('cascade');

			$table->bigInteger('wheel_setting_id')->unsigned()->unique();
			$table->foreign('wheel_setting_id')->references('id')->on('wheel_settings')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('media_wheel_setting');
    }
}
