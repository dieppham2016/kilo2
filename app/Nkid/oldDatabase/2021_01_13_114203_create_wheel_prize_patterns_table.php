<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWheelPrizePatternsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wheel_prize_patterns', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->string('pattern_name')->unique();
            $table->string('pattern_alias')->unique();
            $table->unsignedInteger('max_prize')->default(12);
            $table->json('prize_location')->nullable();
            $table->unsignedInteger('bonus__min')->default(10);
			$table->unsignedInteger('bonus__max')->default(20);
			$table->unsignedInteger('bonus__fixed')->default(10);
			$table->boolean('bonus__enable')->default(FALSE);
			$table->boolean('bonus__increment')->default(TRUE); // increment or fixed
			$table->unsignedInteger('bonus__current')->default(10);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wheel_prize_patterns');
    }
}
