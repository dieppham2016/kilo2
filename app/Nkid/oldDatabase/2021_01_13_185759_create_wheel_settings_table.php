<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWheelSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wheel_settings', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->bigInteger('wheel_pattern_id')->unsigned();
			$table->foreign('wheel_pattern_id')->references('id')->on('wheel_prize_patterns')->onDelete('cascade');

			$table->string('mode__type_prize')->default('Ticket'); // Ticket | Prize
			$table->boolean('mode__return_ticket')->default(TRUE);
			$table->string('display__type_stop')->nullable();
			$table->string('display__type_draw')->nullable();
			$table->boolean('display__show_pointer')->default(TRUE);
			$table->boolean('display__multiple_background_color')->default(FALSE);
			$table->unsignedInteger('display__pointer_position')->default(90); // 0-360 degree
			$table->string('theme__begin')->nullable();
			$table->string('theme__end')->nullable();
			$table->string('theme__winner')->nullable();
			$table->boolean('theme__show_demo')->default(FALSE);
			$table->unsignedInteger('audio__show_demo_volume')->nullable();
			$table->unsignedInteger('audio__game_volume')->default(50);
			$table->unsignedInteger('animation__spins')->default(6); // The number of complete 360 degree rotations the wheel is to do.
			$table->string('animation__type');
			$table->string('animation__easing'); // https://greensock.com/ease-visualizer | Docs: https://greensock.com/docs/
			$table->integer('animation__repeat'); //  times the animation is to repeat, set to -1 for animation to repeat forever
			$table->unsignedInteger('animation__duration'); // seconds
			$table->string('animation__direction');
			$table->boolean('animation__yoyo')->default(FALSE); //  if set to true the animation will reverse back to the start after completing

		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wheel_settings');
    }
}
