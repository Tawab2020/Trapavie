<?php

namespace App\Http\Controllers\Admin\Venue;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Venue\AccommodationTypeRequest;
use App\Models\Venue\AccommodationType;
use Illuminate\Support\Facades\Storage;

class AccommodationTypeController extends Controller
{
    public function index()
    {
        $accommodationType = AccommodationType::all();
        return response()->json($accommodationType, 200);
    }

    public function store(AccommodationTypeRequest $request)
    {
        $accommodationType_data = $request->validated();
        unset($accommodationType_data['image']);
        $accommodationType = AccommodationType::create($accommodationType_data);
        return response()->json($accommodationType, 200);
    }

    public function show(AccommodationType $accommodationType)
    {
        $accommodationType->load('image');
        return response()->json($accommodationType, 200);
    }

    public function update(AccommodationTypeRequest $request, AccommodationType $accommodationType)
    {
        $accommodationType_data = $request->validated();
        if (isset($accommodationType_data['image'])) {
            $accommodationType->uploadImage($accommodationType_data['image']);
        }
        // Switching to builder because Spatie-Translatable doesn't work on models
        $accommodationTypeBuilder = AccommodationType::where('id', $accommodationType->id);
        unset($accommodationType_data['image']);
        $accommodationTypeBuilder->update($accommodationType_data);
        // refresh data
        $accommodationType = AccommodationType::find($accommodationType->id);
        $accommodationType->load('image');
        return response()->json($accommodationType, 200);
    }

    public function uploadIcon(AccommodationType $accommodationType)
    {
        if (request()->hasFile('image')) {
            $accommodationType->uploadImage(request()->file('image'));
            $image_path = request()->file('image')->store('public/venue/data/accommodationTypes');

            $pre_image = $accommodationType->image;
            if ($pre_image) {
                Storage::delete('public/venue/data/accommodationTypes/' . $pre_image->image);
                $accommodationType->image()->first()->update(['image' => basename($image_path)]);
            } else {
                $accommodationType->image()->create(['image' => basename($image_path)]);
            }
            $accommodationType->load('image');
            return response()->json($accommodationType, 200);
        }
    }

    public function destroy(AccommodationType $accommodationType)
    {
        if ($accommodationType->is_used != 0) {
            return response()->json([
                'message' => 'You can not delete a Accommodation Data that has already been used.'
            ], 500);
        }
        $accommodationType->delete();
        return response()->json([
            'message' => 'Accommodation Data Was Deleted Successfully.'
        ], 200);
    }
}
