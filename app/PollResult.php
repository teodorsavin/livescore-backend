<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Container\EntryNotFoundException;

class PollResult extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'poll_result_id', 'poll_option_id', 'poll_id'
    ];

    public static function getPollResults($pollId)
    {
        $poll = Poll::wherePollId($pollId)->with('pollOptions')->first();
        if (empty($poll)) {
            throw new EntryNotFoundException('Poll not found!');
        } else {
            $pollResults = [];
            $results = [];
            $rawResults = self::getResultsGroupedByPollId($poll->poll_id)->toArray();

            foreach ($rawResults as $resultOption) {
                $results[$resultOption['poll_option_id']] = $resultOption['total'];
            }

            foreach ($poll->pollOptions as $option) {
                if (!empty($results[$option->poll_option_id])) {
                    $pollResults[$option->poll_option_value] = $results[$option->poll_option_id];
                } else {
                    $pollResults[$option->poll_option_value] = 0;
                }
            }

            return $pollResults;
        }
    }

    public static function getResultsGroupedByPollId($pollId)
    {
        $pollResults = self::select('poll_option_id', DB::raw('count(*) as total'))
                ->wherePollId($pollId)
                ->groupBy('poll_option_id')
                ->get();

        return $pollResults;
    }
}
