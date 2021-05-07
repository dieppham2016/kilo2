<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWheelPrizesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wheel_prizes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('type'); // Ticket | Prize | Code
			$table->string('rate'); // Rate must be < 100%
			$table->unsignedInteger('amount'); // min:1
			$table->string('more_info')->nullable();
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
        Schema::dropIfExists('wheel_prizes');
    }
}
