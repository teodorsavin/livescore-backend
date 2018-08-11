<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class League extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'league_id', 'country_id', 'league_name',
    ];

    public static function saveDataFromApi(array $leagues)
    {
        $data = [];
        $i = 0;
        foreach ($leagues as $league) {
            $data[$i]['league_id'] = $league['league_id'];
            $data[$i]['country_id'] = $league['country_id'];
            $data[$i]['league_name'] = $league['league_name'];
            $data[$i]['created_at'] = date('Y-m-d H:i:s');
            $data[$i]['updated_at'] = date('Y-m-d H:i:s');
            $i++;
        }

        if (!empty($data)) {
            self::insert($data);
        }

        return true;
    }
}
