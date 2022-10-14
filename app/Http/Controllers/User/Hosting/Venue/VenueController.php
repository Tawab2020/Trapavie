<?php

namespace App\Http\Controllers\User\Hosting\Venue;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Hosting\Venue\BlockedDatesRequest;
use App\Http\Requests\User\Hosting\Venue\SeasonPricingRequest;
use App\Models\Venue\BlockedDates;
use App\Models\Venue\SeasonPricing;
use App\Models\Venue\Venue;
use Illuminate\Http\Request;

class VenueController extends Controller
{

    public function listUserVenues()
    {
        $user = auth('user')->user();
        $venues = $user->venues()->where('is_deleted', 0)->with(['images', 'blockedDates', 'seasonPricings'])->get();
        return response()->json($venues, 200);
    }

    public function pauseVenue(Venue $venue)
    {
        $venue->is_paused = 1;
        $venue->save();
        return response()->json(['message' => 'Venue was paused successfully.'], 200);
    }

    public function activateVenue(Venue $venue)
    {
        $venue->is_paused = 0;
        $venue->save();
        return response()->json(['message' => 'Venue was activated successfully.'], 200);
    }

    public function deleteVenue(Venue $venue)
    {
        $venue->is_deleted = 1;
        $venue->save();
        return response()->json(['message' => 'Venue was deleted successfully.'], 200);
    }

    public function blockDates(BlockedDatesRequest $request)
    {
        $blockedDates_data = $request->validated();
        $blockedDates = BlockedDates::create($blockedDates_data);
        return response()->json($blockedDates, 200);
    }

    public function deleteBlockedDates(BlockedDates $blockedDates)
    {
        $blockedDates->delete();
        return response()->json(['message' => 'Blocked dates were deleted.'], 200);
    }

    public function addSeasonPricing(SeasonPricingRequest $request)
    {
        $seasonPricing_data = $request->validated();
        $seasonPricing = SeasonPricing::create($seasonPricing_data);
        return response()->json($seasonPricing, 200);
    }


    public function deleteSeasonPricing(SeasonPricing $seasonPricing)
    {
        $seasonPricing->delete();
        return response()->json(['message' => 'Season pricing were deleted.'], 200);
    }
}
