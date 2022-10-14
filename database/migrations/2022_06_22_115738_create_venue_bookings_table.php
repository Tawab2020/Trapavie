<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVenueBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('venue_bookings', function (Blueprint $table) {
            $table->id();
            $table->date('start_date');
            $table->date('end_date');
            $table->unsignedInteger('guests');
            // Keep log of every field in venues table here.
            // No need to store related data such as venueData, since we are using soft deletes and we load them through relationships
            $table->json('venue_log')->comment('store venue log except [id, user_id, status, is_paused, is_deleted, step, created_at, updated_at]');
            $table->unsignedBigInteger('user_id')->comment('customer (retreat organizer) ID');
            $table->unsignedTinyInteger('status')->default(0)->comment('0 for REQUESTED, 1 for CONFIRMED by Venue Host, 2 for the DEPOSIT PAID by Retreat Organizer, 3 for FULLY PAID by the Retreat Organizer, 4 for REJECTED by Venue Host, 5 for Cancelled by Venue Host, 6 for Cancelled by Retreat Organizer, 7 for Refunded');
            $table->longText('price')->comment('store the total price for the booking, after calculating any discounts and high low season.');
            $table->longText('cancellation_policy_log')->comment('store the cancellation policy data, since it maybe edited by the admin later.');
            $table->unsignedInteger('venue_id');
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('deposit_paid_at')->nullable();
            $table->timestamp('fully_paid_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamp('refund_paid_at')->nullable();
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
        Schema::dropIfExists('venue_bookings');
    }
}
