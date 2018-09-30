<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Poll;
use App\PollResult;

class PollController extends Controller
{
    public function showPoll($pollId)
    {
        $poll = Poll::wherePollId($pollId)->firstOrFail();
        $pollResults = PollResult::getPollResults($poll->poll_id);

        return response()->json([$poll->toArray(), $pollResults]);
    }
}
