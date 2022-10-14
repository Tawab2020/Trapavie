<?php

namespace App\Http\Controllers\User\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\VenueBookingRequest;
use App\Http\Requests\User\VenueSearchRequest;
use App\Models\User;
use App\Models\Venue\Venue;
use App\Models\VenueBooking;
use DateTime;
use Illuminate\Http\Request;

class VenueController extends Controller
{
    public function homepageVenues()
    {
        $venues = Venue::with('accommodations.bedrooms.bedroomArrangements')->where([['is_deleted', 0], ['status', 2]])->with('images')->orderBy('created_at')->get();
        $user = auth('user')->user();
        if ($user) {
            $venues = $venues->where('user_id', '!=', $user->id);
        }
        return response()->json($venues, 200);
    }

    public function show(Venue $venue)
    {
        if ($venue->is_deleted == 1 || $venue->status != 2) {
            return response()->json(["message" => "Venue not available"], 500);
        }
        $booked_dates = $venue->bookings()->whereIn('status', [1, 2, 3])->get(['start_date as start', 'end_date as end']);
        $venue->booked_dates = $booked_dates;
        $venue->load(['user.userDetail', 'blockedDates', 'venueData.image', 'airports', 'keyPropertyFeatures', 'images', 'generalFacilities', 'indoorFacilities', 'outdoorFacilities', 'indoorYogaEquipments', 'outdoorYogaEquipments', 'surroundings', 'thingsToDo', 'rentals', 'food', 'kitchen', 'accommodations.bedrooms.bedroomArrangements', 'accommodations.amenities', 'accommodations.images', 'lengthOfStayDiscounts', 'mealDiscounts', 'meals', 'cancellationPolicy.cancellationPolicyData']);
        return response()->json($venue, 200);
    }

    public function search(VenueSearchRequest $request)
    {
        $search_data = $request->validated();
        $venues = Venue::where([['is_deleted', 0], ['is_paused', 0], ['status', 2], ['country', $search_data['country']], ['min_guests', '<=', $search_data['guests']]])->get();
        $venues->load(['images', 'accommodations.bedrooms.bedroomArrangements']);
        return response()->json($venues, 200);
    }
}
