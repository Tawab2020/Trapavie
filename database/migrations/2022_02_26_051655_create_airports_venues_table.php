<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAirportsVenuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('airport_venue', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('venue_id');
            $table->unsignedInteger('airport_id');
            $table->string('distance');
            $table->unsignedTinyInteger('type')->default(0)->comment('0 for not provided, 1 for free, 2 for extra charge per guest');
            $table->integer('charge')->nullable();
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
        Schema::dropIfExists('airports_venues');
    }
}
