<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use PragmaRX\Countries\Package\Countries;

class CountryController extends Controller
{
    public function getCountries()
    {
        $countries = new Countries();
        $list = $countries->all()->pluck('name.common')->toArray();
        return response()->json($list, 200);
    }

    public function getShortName($country)
    {
        // dd($country);
        $countries = new Countries();
        $short = $countries->where('name.common', $country)
            ->first()
            ->cca3;
        return response()->json($short, 200);
    }

    public function getTwoCharacterName($country)
    {
        // dd($country);
        $countries = new Countries();
        $short = $countries->where('name.common', $country)
            ->first()->cca2;
        return response()->json($short, 200);
    }

    public function getCurrency()
    {
        $countries = new Countries();
        $country = request('country');
        $currency = $countries->where('name.common', $country)->first()->currencies[0];
        return response()->json($currency, 200);
    }

    public function getStates()
    {
        $countries = new Countries();
        $country = request('country');
        $states = $countries->where('name.common', $country)
            ->first()
            ->hydrateStates()
            ->states
            ->sortBy('name')
            ->pluck('name');
        return response()->json($states, 200);
    }

    public function getCities()
    {
        $countries = new Countries();
        $state = request('state');
        $country = request('country');
        $cities = $countries->where('name.common', $country)->first()->hydrateCities()->cities->where('adm1name', $state)->pluck('name');
        return response()->json($cities, 200);
    }
}
