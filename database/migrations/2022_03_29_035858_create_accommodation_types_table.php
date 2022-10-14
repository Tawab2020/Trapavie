<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccommodationTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accommodation_types', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->unsignedTinyInteger('ask_for_rooms')->default(1)->comment('ask for rooms and bathrooms or not? 1 does, 0 does not');
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
        Schema::dropIfExists('accommodation_types');
    }
}
