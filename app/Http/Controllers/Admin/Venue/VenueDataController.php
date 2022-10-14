<?php

namespace App\Http\Controllers\Admin\Venue;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Venue\VenueDataRequest;
use App\Models\Venue\VenueData;
use Illuminate\Support\Facades\Storage;

class VenueDataController extends Controller
{
    public function index()
    {
        $venueData = VenueData::all();
        return response()->json($venueData, 200);
    }

    public function store(VenueDataRequest $request)
    {
        $venueDatum_data = $request->validated();
        $venueDatum = VenueData::create($venueDatum_data);
        return response()->json($venueDatum, 200);
    }

    public function show(VenueData $venueDatum)
    {
        $venueDatum->load('image');
        return response()->json($venueDatum, 200);
    }

    public function update(VenueDataRequest $request, VenueData $venueDatum)
    {
        $venueDatum_data = $request->validated();
        $venueDatum->update($venueDatum_data);
        return response()->json($venueDatum, 200);
    }

    public function uploadIcon(VenueData $venueData)
    {
        if (request()->hasFile('image')) {
            $image_path = request()->file('image')->store('public/venue/data');

            $pre_image = $venueData->image;
            if ($pre_image) {
                Storage::delete('public/venue/data/' . $pre_image->image);
                $venueData->image()->first()->update(['image' => basename($image_path)]);
            } else {
                $venueData->image()->create(['image' => basename($image_path)]);
            }
            return response()->json($venueData, 200);
        }
    }

    public function destroy(VenueData $venueDatum)
    {
        if ($venueDatum->is_used != 0) {
            return response()->json([
                'message' => 'You can not delete a Venue Data that has already been used.'
            ], 500);
        }
        $venueDatum->delete();
        return response()->json([
            'message' => 'Venue Data Was Deleted Successfully.'
        ], 200);
    }
}
