<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->string('image');
            $table->string('caption')->nullable();
            $table->unsignedTinyInteger('type')->nullable()->comment('1 for banner image, 2 for general, 3 for indoor, 4 for outdoor, 5 for indoor yoga, 6 for outdoor yoga, 7 for surroundings, 8 for things to do, 9 for rentals, 10 for food, 11 for kitchen, 12 for accomodation');
            $table->unsignedBigInteger('imageable_id');
            $table->string('imageable_type'); // morphing for retreats and venues
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
        Schema::dropIfExists('images');
    }
}
