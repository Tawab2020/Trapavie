<?php

namespace App\Http\Controllers\Admin\Venue;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Venue\VenueDataCategoryRequest;
use App\Models\Venue\VenueDataCategory;
use Illuminate\Support\Facades\Storage;

class VenueDataCategoryController extends Controller
{
    public function index()
    {
        $venueDataCategory = VenueDataCategory::all();
        return response()->json($venueDataCategory, 200);
    }

    public function store(VenueDataCategoryRequest $request)
    {
        $venueDataCategory_data = $request->validated();
        $venueDataCategory = VenueDataCategory::create($venueDataCategory_data);
        return response()->json($venueDataCategory, 200);
    }

    public function show(VenueDataCategory $venueDataCategory)
    {
        $venueDataCategory->load('image');
        return response()->json($venueDataCategory, 200);
    }

    public function update(VenueDataCategoryRequest $request, VenueDataCategory $venueDataCategory)
    {
        $venueDataCategory_data = $request->validated();
        $venueDataCategory->update($venueDataCategory_data);
        return response()->json($venueDataCategory, 200);
    }

    public function uploadIcon(VenueDataCategory $venueDataCategory)
    {
        if (request()->hasFile('image')) {
            $image_path = request()->file('image')->store('public/venue/data');

            $pre_image = $venueDataCategory->image;
            if ($pre_image) {
                Storage::delete('public/venue/data/' . $pre_image->image);
                $venueDataCategory->image()->first()->update(['image' => basename($image_path)]);
            } else {
                $venueDataCategory->image()->create(['image' => basename($image_path)]);
            }
            $venueDataCategory->load('image');
            return response()->json($venueDataCategory, 200);
        }
    }

    public function destroy(VenueDataCategory $venueDataCategory)
    {
        // Does not apply.
    }
}
