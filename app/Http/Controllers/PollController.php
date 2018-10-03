<?php

namespace App\Http\Controllers;

use App\PollOption;
use Illuminate\Http\Request;
use App\Poll;
use App\PollResult;

class PollController extends Controller
{
    public function getPollResults($pollId)
    {
        $poll = Poll::wherePollId($pollId)->firstOrFail();
        $pollResults = PollResult::getPollResults($poll->poll_id);

        return response()->json([$poll->toArray(), $pollResults]);
    }

    public function showPoll($pollId)
    {
        $poll = Poll::wherePollId($pollId)->with('pollOptions')->firstOrFail();

        return response()->json([$poll->toArray()]);
    }

    public function vote($pollId, Request $request)
    {
        $this->validate($request, [
            'option' => 'required|min:1'
        ]);

        $poll = Poll::wherePollId($pollId)->firstOrFail();

        $pollResult = PollResult::create([
            'poll_id' => $poll->poll_id,
            'poll_option_id' => $request->get('option'),
        ]);

        return response()->json($pollResult->toArray());
    }

    public function getIp()
    {
        $ip = $_SERVER['REMOTE_ADDR'];

        return $ip;
    }
}
