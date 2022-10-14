<?php

namespace App\Http\Controllers\Admin\Venue;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Venue\PropertyTypeRequest;
use App\Models\Venue\PropertyType;

class PropertyTypeController extends Controller
{
    public function index()
    {
        $propertyType = PropertyType::all();
        return response()->json($propertyType, 200);
    }

    public function store(PropertyTypeRequest $request)
    {
        $propertyType_data['title'] = $request->validated()['title'];
        $accommodationTypes = $request->validated()['accommodation_types'];
        $propertyType = PropertyType::create($propertyType_data);
        $propertyType->attachAccommodationTypes($accommodationTypes);
        return response()->json($propertyType, 200);
    }

    public function show(PropertyType $propertyType)
    {
        return response()->json($propertyType->load('accommodationTypes'), 200);
    }

    public function update(PropertyTypeRequest $request, PropertyType $propertyType)
    {
        $propertyType_data['title'] = $request->validated()['title'];
        $accommodationTypes = $request->validated()['accommodation_types'];
        $propertyType->update($propertyType_data);
        $propertyType->attachAccommodationTypes($accommodationTypes);
        return response()->json($propertyType, 200);
    }

    public function destroy(PropertyType $propertyType)
    {
        if ($propertyType->is_used != 0) {
            return response()->json([
                'message' => 'You can not delete a Property Type that has already been used.'
            ], 500);
        }
        $propertyType->delete();
        return response()->json([
            'message' => 'Property Type was deleted successfully.'
        ], 200);
    }
}
