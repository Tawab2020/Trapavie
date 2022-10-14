<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVenueDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('venue_discounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('venue_id');
            $table->unsignedTinyInteger('type')->default(0)->comment('0 for lenght of stay discount in days, 1 for meal discound in number of people');
            $table->unsignedInteger('from');
            $table->unsignedInteger('to');
            $table->unsignedInteger('percentage');
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
        Schema::dropIfExists('venue_discounts');
    }
}
