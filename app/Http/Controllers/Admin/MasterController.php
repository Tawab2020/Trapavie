<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FaqCat;
use App\Models\Language;
use App\Models\Venue\AccommodationType;
use App\Models\Venue\PropertyType;
use App\Models\Venue\VenueDataCategory;

class MasterController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }

    public function initialize()
    {
        $languages = Language::all();
        $faqCats = FaqCat::with(['parent', 'children.faqs'])->get();
        $venueDataCategories = VenueDataCategory::with(['venueData.image', 'image'])->get();
        $propertyTypes = PropertyType::with('accommodationTypes')->get();
        $accommodationTypes = AccommodationType::where('user_id', NULL)->with('image')->get();
        return response()->json([
            "languages" => $languages,
            "faqCats" => $faqCats,
            'venueDataCategories' => $venueDataCategories,
            'propertyTypes' => $propertyTypes,
            'accommodationTypes' => $accommodationTypes
        ], 200);
    }
}
