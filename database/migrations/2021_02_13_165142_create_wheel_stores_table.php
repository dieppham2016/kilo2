<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWheelStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	// coin, ticket less
        Schema::create('wheel_stores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('coin')->default(0);
            $table->unsignedInteger('ticket_remaining')->nullable();
            $table->json('ticket_remaining_by_session')->nullable();
            $table->json('current_error')->nullable(); // EI: Error-Internet | EH: Error-Hardware | ET: Error-Ticket
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
        Schema::dropIfExists('wheel_stores');
    }
}
