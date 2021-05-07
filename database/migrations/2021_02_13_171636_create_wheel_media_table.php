<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWheelMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wheel_media', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('demo_screen')->nullable();
            $table->string('demo_music')->nullable();
			$table->string('play_screen')->nullable();
			$table->string('play_music')->nullable();
            $table->string('congratulation_screen')->nullable();
            $table->string('congratulation_music')->nullable();
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
        Schema::dropIfExists('wheel_media');
    }
}
