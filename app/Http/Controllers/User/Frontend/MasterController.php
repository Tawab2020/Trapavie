<?php

namespace App\Http\Controllers\User\Frontend;

use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\Currency;
use App\Models\Language;
use App\Models\Venue\CancellationPolicyData;
use App\Models\Venue\PropertyType;
use App\Models\Venue\VenueDataCategory;

class MasterController extends Controller
{
    public function index()
    {
        return view('user.frontend.index');
    }

    public function initialize()
    {
        $languages = Language::where('is_active', 1)->get();
        $text = trans('text', [], "fr");
        $about = About::where('id', 1)->first();
        $currencies = Currency::orderBy('created_at')->get();
        $propertyTypes = PropertyType::with('accommodationTypes')->get();
        $venueDataCategories = VenueDataCategory::with('image')->get();

        if (auth('user')->user()) {
            $propertyTypes = PropertyType::with('userAccommodationTypes')->get();
            $venueDataCategories = VenueDataCategory::with(['userVenueData', 'image'])->get();
            return response()->json([
                "languages" => $languages,
                "text" => $text,
                "about" => $about,
                "currencies" => $currencies,
                "propertyTypes" => $propertyTypes,
                "propertyTypes" => $propertyTypes,
                "venueDataCategories" => $venueDataCategories
            ], 200);
        }
        return response()->json([
            "languages" => $languages,
            "text" => $text,
            "about" => $about,
            "propertyTypes" => $propertyTypes,
            "currencies" => $currencies,
            "venueDataCategories" => $venueDataCategories
        ], 200);
    }

    public function cancellationPolicies()
    {
        $cancellationPolicies = CancellationPolicyData::all();
        return response()->json($cancellationPolicies, 200);
    }

    public function loadTranslations($lang)
    {
        $text = trans('text', [], $lang);
        return response()->json($text, 200);
    }
}
