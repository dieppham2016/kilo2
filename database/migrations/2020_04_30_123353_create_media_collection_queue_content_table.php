<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediaCollectionQueueContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media_collection_queue_content', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('media_collection_id')->unsigned()->index();
            $table->foreign('media_collection_id')->references('id')->on('media_collections')->onDelete('cascade');

            $table->bigInteger('queue_content_id')->unsigned()->index();
            $table->foreign('queue_content_id')->references('id')->on('queue_contents')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('media_collection_queue_content');
    }
}
