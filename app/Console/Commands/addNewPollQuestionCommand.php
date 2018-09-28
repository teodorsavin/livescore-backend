<?php

namespace App\Console\Commands;

use App\Poll;
use App\PollOption;

use Exception;
use Illuminate\Console\Command;

class AddNewPollQuestionCommand extends Command {
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = "poll:create";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Create a new poll";

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $startDate = '';
        $endDate = '';
        $noOptions = null;
        $options = [];

        $question = $this->getQuestion();
        $startDate = $this->getStartDate();
        $endDate = $this->getEndDate();

        while (empty($noOptions) || $noOptions <= 0) {
            $noOptions = $this->ask('How many options will you have ?');
        }

        for ($i = 1; $i<=$noOptions; $i++) {
            $options[$i] = null;
            while (empty($options[$i])) {
                $options[$i] = $this->ask("What is the option {$i}?");
            }
        }

        $active = $this->choice('Make it active right away ?', [0, 1], 1);

        // Sumarise
        $this->info("\n--------------------------------------------------------------------------------\n");
        $this->info("This is your poll: \n");
        $this->info($question);
        foreach ($options as $key => $option) {
            $this->info($key . ") " . $option);
        }

        $this->info("\n");
        if ($this->confirm('Do you wish to create this ?')) {
            $this->addPollToDb($question, $startDate, $endDate, $options, $active);
            $this->progressBar($noOptions);
        }

        $this->printDone();
    }

    private function getQuestion()
    {
        $question = '';
        while (empty($question)) {
            $question = $this->ask('What is the poll question?');
        }
        return $question;
    }

    private function getStartDate()
    {
        $startDate = '';
        while (empty($startDate)) {
            $startDate = $this->ask('What is the poll start date? (Y-m-d)');
        }
        $startDate .= ' 00:00:00';
        return $startDate;
    }

    private function getEndDate()
    {
        $endDate = '';
        while (empty($endDate)) {
            $endDate = $this->ask('What is the poll end date? (Y-m-d)');
        }
        $endDate .= ' 23:59:59';
        return $endDate;
    }

    private function addPollToDb($question, $startDate, $endDate, $options, $active)
    {
        $poll = Poll::create([
            'poll_question' => $question,
            'poll_start_date' => $startDate,
            'poll_end_date' => $endDate,
            'poll_active' => $active
        ]);

        foreach ($options as $option) {
            PollOption::create([
                'poll_option_value' => $option,
                'poll_id' => $poll->id
            ]);
        }

        return true;
    }

    private function progressBar($noOptions)
    {
        $this->info("\n");
        $bar = $this->output->createProgressBar($noOptions*5);
        for ($j = 1; $j <=$noOptions*5; $j++) {
            usleep(100000);
            $bar->advance();
        }
    }

    private function printDone()
    {
        $this->info("\n");
        $this->info('Done!');
        $this->info("\n");
    }
}
