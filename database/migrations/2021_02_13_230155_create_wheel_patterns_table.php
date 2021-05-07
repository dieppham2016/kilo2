<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWheelPatternsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wheel_patterns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('alias');
            $table->json('prizes_location');
			$table->string('type'); // Ticket | Image
			$table->bigInteger('prize_id')->nullable(); // for the bonus type is image
			$table->unsignedInteger('bonus_min');
			$table->unsignedInteger('bonus_max');
			$table->unsignedInteger('bonus_current');
			$table->unsignedInteger('bonus_fixed')->nullable();
			$table->unsignedInteger('bonus_rate'); // Max: 20%
			$table->boolean('bonus_auto_increment')->default(true);
			$table->boolean('bonus_enable')->default(true);
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
        Schema::dropIfExists('wheel_patterns');
    }
}
