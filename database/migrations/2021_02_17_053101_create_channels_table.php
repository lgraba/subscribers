<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateChannelsTable
 * Create a channels table to represent YouTube channels
 */
class CreateChannelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Name would be more verbose + additional columns in a full-scale application
        Schema::create('channels', function (Blueprint $table) {
            $table->id();
            $table->string('youtube_id')->comment('The YouTube channel id');
            $table->string('title')->comment('The YouTube channel title');
            $table->string('description')->comment('The YouTube channel description');
            $table->date('retrieved_at')->nullable()
                ->comment('The last datetime channel statistics were retrieved');
            $table->timestamps();

            $table->index('youtube_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('channels');
    }
}
