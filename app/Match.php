<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'match_id', 'country_id', 'league_id', 'goalscorer', 'lineup', 'statistics', 'cards',
        'match_date', 'match_time', 'match_status', 'match_live', 'match_hometeam_name',
        'match_hometeam_score', 'match_hometeam_system', 'match_hometeam_extra_score',
        'match_hometeam_penalty_score', 'match_hometeam_halftime_score', 'match_awayteam_name',
        'match_awayteam_score', 'match_awayteam_system', 'match_awayteam_extra_score',
        'match_awayteam_penalty_score', 'match_awayteam_halftime_score',
    ];

    public static function saveDataFromApi(array $matches)
    {
        $data = [];
        $i = 0;
        foreach ($matches as $matchData) {
            if ($matchData['match_status'] != 'FT') {
                $data[$i]['match_id'] = $matchData['match_id'];
                $data[$i]['country_id'] = $matchData['country_id'];
                $data[$i]['league_id'] = $matchData['league_id'];

                // match details
                $data[$i]['match_date'] = $matchData['match_date'];
                $data[$i]['match_time'] = $matchData['match_time'];
                $data[$i]['match_status'] = $matchData['match_status'];
                $data[$i]['match_live'] = $matchData['match_live'];

                // home team
                $data[$i]['match_hometeam_name'] = $matchData['match_hometeam_name'];
                $data[$i]['match_hometeam_score'] = $matchData['match_hometeam_score'];
                $data[$i]['match_hometeam_system'] = $matchData['match_hometeam_system'];
                $data[$i]['match_hometeam_extra_score'] = $matchData['match_hometeam_extra_score'];
                $data[$i]['match_hometeam_penalty_score'] = $matchData['match_hometeam_penalty_score'];
                $data[$i]['match_hometeam_halftime_score'] = $matchData['match_hometeam_halftime_score'];

                // away team
                $data[$i]['match_awayteam_name'] = $matchData['match_awayteam_name'];
                $data[$i]['match_awayteam_score'] = $matchData['match_awayteam_score'];
                $data[$i]['match_awayteam_system'] = $matchData['match_awayteam_system'];
                $data[$i]['match_awayteam_extra_score'] = $matchData['match_awayteam_extra_score'];
                $data[$i]['match_awayteam_penalty_score'] = $matchData['match_awayteam_penalty_score'];
                $data[$i]['match_awayteam_halftime_score'] = $matchData['match_awayteam_halftime_score'];

                // details
                $data[$i]['lineup'] = json_encode($matchData['lineup']);
                $data[$i]['statistics'] = json_encode($matchData['statistics']);
                $data[$i]['cards'] = json_encode($matchData['cards']);
                $data[$i]['goalscorer'] = json_encode($matchData['goalscorer']);

                $data[$i]['created_at'] = date('Y-m-d H:i:s');
                $data[$i]['updated_at'] = date('Y-m-d H:i:s');
                $i++;
            }
        }

        if (!empty($data)) {
            self::insert($data);
        }

        return true;
    }

    public static function getLastInsertedDateByLeagueId($leagueId)
    {
        $lastMatch = self::where('league_id', $leagueId)->orderBy('match_date', 'desc')->get()->first();
        return $lastMatch;
    }
}
