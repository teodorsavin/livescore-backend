<?php

namespace App\Console\Commands;

use App\Poll;
use App\PollResult;

use Exception;
use Illuminate\Console\Command;

class GetPollResultsCommand extends Command {
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = "poll:results {pollId}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Get responses to poll question";

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $pollId = $this->argument('pollId');

        $poll = Poll::wherePollId($pollId)->with('pollOptions')->first();
        if (empty($poll)) {
            $this->errorNotFound();
            return;
        } else {
            $this->info($poll->poll_question);

            print_r(PollResult::getPollResults($poll->poll_id));
            $this->printDone();
        }
    }

    private function errorNotFound()
    {
        $this->info("\n");
        $this->error('Poll not found');
        $this->info("\n");
    }

    private function printDone()
    {
        $this->info("\n");
        $this->info('Done!');
        $this->info("\n");
    }
}
