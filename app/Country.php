<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'country_id', 'country_name',
    ];

    public static function saveDataFromApi(array $countries)
    {
        $data = [];
        $i = 0;
        foreach ($countries as $country) {
            $data[$i]['country_id'] = $country['country_id'];
            $data[$i]['country_name'] = $country['country_name'];
            $data[$i]['created_at'] = date('Y-m-d H:i:s');
            $data[$i]['updated_at'] = date('Y-m-d H:i:s');
            $i++;
        }

        if (!empty($data)) {
            self::insert($data);
        }

        return true;
    }

    /**
     * Get the leagues for the country.
     */
    public function leagues()
    {
        return $this->hasMany('App\League', 'country_id', 'country_id');
    }

    public static function getAllLeaguesByCountry()
    {
        return self::with('leagues')->get();
    }
}
