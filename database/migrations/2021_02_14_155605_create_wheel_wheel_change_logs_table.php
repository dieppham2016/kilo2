<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWheelWheelChangeLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wheel_change_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('user_name');
            $table->string('action');
            $table->string('module');
            $table->json('from')->nullable();
            $table->json('to')->nullable();
            $table->string('status');
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
        Schema::dropIfExists('wheel_change_logs');
    }
}
