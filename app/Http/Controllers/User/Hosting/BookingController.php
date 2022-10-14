<?php

namespace App\Http\Controllers\User\Hosting;

use App\Http\Controllers\Controller;
use App\Models\VenueBooking;
use App\Notifications\RetreatOrganizer\BookingConfirmed;
use App\Notifications\RetreatOrganizer\BookingRejected;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;

class BookingController extends Controller
{
    public function listVenueBookings()
    {
        $user = Auth('user')->user();
        $user->load('venueBookings.user')->get();
        return response()->json($user->venueBookings, 200);
    }

    public function show(VenueBooking $venueBooking)
    {
        $venueBooking->load(['venue.images', 'venue.accommodations.bedrooms', 'user']);
        return response()->json($venueBooking, 200);
    }

    public function confirmVenueBooking(VenueBooking $venueBooking)
    {
        $venueBooking->status = 1;
        $venueBooking->confirmed_at = Carbon::now();
        $venueBooking->save();
        // Notify booker (retreat organizer)
        $venueBooking->user->notify(new BookingConfirmed($venueBooking));

        $venueBooking->load('user');

        return response()->json($venueBooking, 200);
    }

    public function rejectVenueBooking(VenueBooking $venueBooking)
    {
        $venueBooking->status = 4;
        $venueBooking->rejected_at = Date::now();
        $venueBooking->save();
        $venueBooking->load('user');
        // TODO: Send the notification here
        $venueBooking->user->notify(new BookingRejected($venueBooking));
        return response()->json($venueBooking, 200);
    }
    public function freshTimestamp()
    {
        return Date::now();
    }
}
