<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVenuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('venues', function (Blueprint $table) {
            // General
            $table->id();
            $table->unsignedInteger('user_id');
            $table->unsignedTinyInteger('status')->default(0)->comment('0 for in progress, 1 for pending publish by admin, 2 for published, 3 for rejected');
            $table->unsignedTinyInteger('is_paused')->default(0)->comment('0 for in active, 1 for paused');
            $table->unsignedTinyInteger('is_deleted')->default(0)->comment('0 for not deleted, 1 for deleted');
            $table->unsignedTinyInteger('property_type_id');
            $table->unsignedTinyInteger('currency_id');
            $table->unsignedTinyInteger('is_shared');
            $table->text('name');
            $table->longText('description')->nullable();

            // Address
            $table->string('country');
            $table->text('street');
            $table->text('street2')->nullable();
            $table->string('state');
            $table->string('city');
            $table->string('zip');
            $table->string('lat')->comment('Latitude');
            $table->string('lng')->comment('Longitude');

            //ALL FIELDS AFTER THIS ARE NULLABLE BECAUSE FIRST STEP MUST BE STORED IN DATABASE WITHOUT THE BELOW DATA
            //Airport
            $table->longText('arrival_info')->nullable();

            // Yoga
            $table->unsignedInteger('yoga_spaces')->nullable()->comment('null for step not completed, 0 for none, 1 for indoor, 2 for outdoor, 3 for both');
            $table->unsignedInteger('indoor_capacity')->default(0)->nullable();
            $table->unsignedInteger('outdoor_capacity')->default(0)->nullable();

            // Kitchen
            $table->unsignedInteger('dining_capacity')->nullable();

            // Accomodation
            $table->unsignedInteger('min_guests')->nullable();

            // Pricing
            $table->unsignedInteger('price_per_night')->nullable();
            $table->unsignedInteger('guests_included')->default(0)->comment('guests included in price per night');
            $table->unsignedInteger('additional_guest_price')->default(0)->comment('price for every additional guest per nigth. only in flexible.');
            $table->boolean('has_pricing_discounts')->default(false);

            //Retreat Organizer
            $table->boolean('free_accommodation_for_retreat_organizer')->default(false);
            $table->unsignedInteger('min_guests_for_free_accommodation')->default(0);
            $table->unsignedInteger('min_nights_for_free_accommodation')->default(0);
            $table->boolean('free_meal_for_retreat_organizer')->default(false);
            $table->unsignedInteger('min_guests_for_free_meal')->default(0);
            $table->unsignedInteger('min_nights_for_free_meal')->default(0);

            // Check in Check out
            $table->unsignedInteger('min_nights')->default(1);
            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();
            $table->unsignedInteger('extra_time_between_bookings')->default(0);

            //Step Checker
            $table->integer('step')->default(0);

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
        Schema::dropIfExists('venues');
    }
}
