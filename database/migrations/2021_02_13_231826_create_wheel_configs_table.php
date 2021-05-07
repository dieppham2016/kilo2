<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWheelConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wheel_configs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('io_ticket_pwm')->default(100); // ms
            $table->boolean('io_ticket_input')->default(FALSE); // HIGH | LOW <=> TRUE | FALSE
            $table->boolean('io_ticket_output')->default(FALSE); // HIGH | LOW <=> TRUE | FALSE
            $table->unsignedInteger('time_back_demo')->default(3); // seconds
            $table->unsignedInteger('time_auto_play')->default(30); // seconds
            $table->unsignedInteger('time_show_congratulation_short')->default(5); // seconds
            $table->unsignedInteger('time_show_congratulation_long')->default(30); // seconds
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wheel_configs');
    }
}
