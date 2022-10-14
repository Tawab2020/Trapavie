<?php

namespace App\Jobs;

use App\Models\Venue\Venue;
use App\Models\VenueBooking;
use App\Notifications\RetreatOrganizer\BookingCancelledNoDeposit;
use App\Notifications\RetreatOrganizer\DepositReminder;
use App\Notifications\RetreatOrganizer\PayRemainingBalance;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendReminderEmails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $date = Carbon::now()->toDateString();

        // Send deposit reminder notifications
        $tobeDepositedBookings = VenueBooking::where('status', 1)->where('start_date', '>', $date)->get();
        foreach ($tobeDepositedBookings as $booking) {
            $booking->user->notify(new DepositReminder($booking));
        }

        // Send booking cancelled due to deposit not paid notifications
        $expiredBookings = VenueBooking::where('status', 1)->where('start_date', '=', $date)->get();
        foreach ($expiredBookings as $booking) {
            $booking->user->notify(new BookingCancelledNoDeposit($booking));
        }

        // Send pay remaining balance notifications
        $halfPaidBookings = VenueBooking::where('status', 2)->where('start_date', '>', $date)->get();
        foreach ($halfPaidBookings as $booking) {
            $shouldBePaidInDays = $booking->cancellation_policy_log['remaining_balance_in_days'];
            $shouldBePaidAt = Carbon::createFromFormat('Y-m-d', $booking->start_date)->subDays($shouldBePaidInDays);
            // send 15, 10, 5, and 1 days before the shouldBePaidAt date
            $days = [1, 5, 10, 15];
            if (in_array($shouldBePaidAt->diffInDays($date), $days)) {
                $booking->user->notify(new PayRemainingBalance($booking));
            }
        }
    }
}
