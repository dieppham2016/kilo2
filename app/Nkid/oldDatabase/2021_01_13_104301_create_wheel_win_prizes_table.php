<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWheelWinPrizesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wheel_win_prizes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('type')->default('Ticket'); // Ticket | Bonus | Prize
            $table->string('amount');
            $table->string('background')->nullable();
            $table->string('text_color')->nullable();
            $table->unsignedInteger('rate'); //  1%-100%
            $table->text('more_info')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wheel_win_prizes');
    }
}
