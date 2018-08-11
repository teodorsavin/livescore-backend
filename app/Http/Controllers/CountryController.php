<?php

namespace App\Http\Controllers;

use App\Country;
use App\League;
use Illuminate\Http\Request;

class CountryController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function showCountries() {
        return response()->json(Country::all());
    }

    public function showLeaguesByCountry($countryId) {
        return response()->json(League::where('country_id', $countryId)->get());
    }
}
