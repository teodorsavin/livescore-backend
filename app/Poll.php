<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
            'poll_question', 'poll_start_date', 'poll_end_date', 'poll_active'
    ];

    /**
     * Get the options associated with the question.
     */
    public function pollOptions()
    {
        return $this->hasMany('App\PollOption', 'poll_id', 'poll_id');
    }

    /**
     * Get the results associated with the question.
     */
    public function pollResults()
    {
        return $this->hasMany('App\PollResult', 'poll_id', 'poll_id');
    }
}
