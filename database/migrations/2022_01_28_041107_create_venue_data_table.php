<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVenueDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('venue_data', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->unsignedInteger('venue_data_category_id');
            $table->boolean('is_used')->default(0);
            $table->unsignedInteger('user_id')->nullable()->comment('if the venueData is added by a user');
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
        Schema::dropIfExists('venue_data');
    }
}
