<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWheelPrizePatternWheelWinPrizeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wheel_prize_pattern_wheel_win_prize', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->bigInteger('wp_id')->unsigned();
			$table->foreign('wp_id')->references('id')->on('wheel_win_prizes')->onDelete('cascade');

			$table->bigInteger('wpp_id')->unsigned();
			$table->foreign('wpp_id')->references('id')->on('wheel_prize_patterns')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wheel_prize_pattern_wheel_win_prize');
    }
}
