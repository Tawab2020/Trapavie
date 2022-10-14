<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAboutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('abouts', function (Blueprint $table) {
            $table->id();
            $table->text('main_title');
            $table->text('secondary_title');
            $table->text('excerpt');
            $table->text('description');
            $table->text('btn_text');
            $table->string('btn_url');
            $table->string('bg_image')->nullable();
            $table->string('image')->nullable();
            $table->text('team_title');
            $table->text('team_excerpt');
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
        Schema::dropIfExists('abouts');
    }
}
