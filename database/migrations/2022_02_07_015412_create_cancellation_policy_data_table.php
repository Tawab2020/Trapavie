<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCancellationPolicyDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cancellation_policy_data', function (Blueprint $table) {
            $table->id();
            $table->text('title')->comment('title of policy');
            $table->unsignedInteger('deposit')->comment('deposit to secure the booking');
            $table->unsignedTinyInteger('refund_percentage')->nullable()->comment('if null, then its not refundable.');
            $table->unsignedInteger('days_before_arrival')->nullable()->comment('should be canceled before this number of days to be available for refund');
            $table->unsignedInteger('remaining_balance_in_days')->comment('remaining balancce should be paid before this many days of arrival.');
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
        Schema::dropIfExists('cancellation_policy_data');
    }
}
