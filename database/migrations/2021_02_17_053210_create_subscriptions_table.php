<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateSubscriptionsTable
 * Create a subscriptions table to hold daily subscription counts
 */
class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Name would be more verbose (e.g. channel_statistics) in a full-scale application
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('channel_id')
                ->comment('The channel this subscription record pertains to')
                ->constrained();

            $table->integer('count')->default(0)
                ->comment('The number of subscriptions for the given channel');

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
        Schema::dropIfExists('subscriptions');
    }
}
