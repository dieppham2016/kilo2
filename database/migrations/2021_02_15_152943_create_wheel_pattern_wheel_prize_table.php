<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWheelPatternWheelPrizeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wheel_pattern_wheel_prize', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->bigInteger('prize_id')->unsigned();
			$table->foreign('prize_id')->references('id')->on('wheel_prizes')->onDelete('cascade');

			$table->bigInteger('pattern_id')->unsigned();
			$table->foreign('pattern_id')->references('id')->on('wheel_patterns')->onDelete('cascade');

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
        Schema::dropIfExists('wheel_pattern_wheel_prize');
    }
}
