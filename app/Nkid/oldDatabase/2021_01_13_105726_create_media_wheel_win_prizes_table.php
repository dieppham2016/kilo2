<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediaWheelWinPrizesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media_wheel_win_prizes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('media_id')->unsigned()->unique();
			$table->foreign('media_id')->references('id')->on('media')->onDelete('cascade');

			$table->bigInteger('wheel_win_prize_id')->unsigned()->unique();
			$table->foreign('wheel_win_prize_id')->references('id')->on('wheel_win_prizes')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('media_wheel_win_prizes');
    }
}
