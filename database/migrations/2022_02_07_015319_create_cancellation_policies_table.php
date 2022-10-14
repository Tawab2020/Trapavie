<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCancellationPoliciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cancellation_policies', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('venue_id');
            $table->unsignedTinyInteger('cancellation_policy_data_id')->nullable()->comment('if null, then it is a custom policy');
            $table->unsignedInteger('deposit')->nullable();
            $table->unsignedTinyInteger('refund_percentage')->nullable();
            $table->unsignedInteger('days_before_arrival')->nullable();
            $table->unsignedInteger('remaining_balance_in_days')->nullable();
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
        Schema::dropIfExists('cancellation_policies');
    }
}
