<?php

namespace App\Http\Controllers;

use App\Match;
use App\Country;
use App\League;
use Illuminate\Http\Request;
use GuzzleHttp;
use GuzzleHttp\Client;

class MatchController extends Controller
{
    private $client;
    private $apiKey;
    private $apiUrl;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = env('API_FOOTBALL_API_KEY');
        $this->apiUrl = env('API_FOOTBALL_API_URL');
    }

    private function getResultsFromApi($leagueId) {
        $lastMatchDate = Match::getLastInsertedDateByLeagueId($leagueId);
        $startDate = ($lastMatchDate !== null) ? $date = date("Y-m-d", strtotime("+1 day", strtotime($lastMatchDate->match_date))) : $startDate = date('Y').'-07-01';
        $today = date('Y-m-d');

        if ($startDate === $today) {
            return;
        }

        $res = $this->client->get($this->apiUrl.'?action=get_events&from=' . $startDate . '&to=' . $today . '&league_id=' . $leagueId . '&APIkey=' . $this->apiKey);
        $contents = json_decode($res->getBody()->getContents(), true);
        
        if (!isset($contents['error'])) {
            Match::saveDataFromApi($contents);
        }
        return true;
    }

    public function showCompletedMatches($leagueId) {
        // Update results first
        $this->getResultsFromApi($leagueId);

        return response()->json(Match::where('league_id', $leagueId)->get());
    }

    // seeders
    private function saveCountriesFromApi() {
        $res = $this->client->get($this->apiUrl.'?action=get_countries&APIkey=' . $this->apiKey);
        Country::saveDataFromApi(json_decode($res->getBody()->getContents(), true));
    }

    private function saveCompetitionsFromApi($countryId) {
        $res = $this->client->get($this->apiUrl.'?action=get_leagues&country_id=' . $countryId . '&APIkey=' . $this->apiKey);
        League::saveDataFromApi(json_decode($res->getBody()->getContents(), true));
    }

    private function getAllCompetitions() {
        $countries = Country::all();
        foreach ($countries as $country) {
            $this->saveCompetitionsFromApi($country->country_id);
        }
    }
}
