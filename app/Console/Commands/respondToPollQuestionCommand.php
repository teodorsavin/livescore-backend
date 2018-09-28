<?php

namespace App\Console\Commands;

use App\Poll;
use App\PollResults;

use Exception;
use Illuminate\Console\Command;

class RespondToPollQuestionCommand extends Command {
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = "poll:respond {pollId}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Respond to a poll question";

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

            $pollOptions = [];
            foreach ($poll->pollOptions as $option) {
                $pollOptions[$option->poll_option_id] = $option->poll_option_value;
            }
            $pollResponseOption = $this->choice($poll->poll_question, $pollOptions);
            $pollResponseOptionId = array_search($pollResponseOption, $pollOptions);

            $pollResult = PollResults::create([
                'poll_id' => $poll->poll_id,
                'poll_option_id' => $pollResponseOptionId,
            ]);

            print_r($pollResult->toArray());
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
