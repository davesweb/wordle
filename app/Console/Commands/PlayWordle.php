<?php

namespace App\Console\Commands;

use App\Game\Enums\Result;
use App\Game\Game;
use Exception;
use Illuminate\Console\Command;

class PlayWordle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'play';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Play Wordle';

    public function handle(Game $game): int
    {
        $game->start();

        while($game->isPlaying()) {
            $guess = $this->ask('Guess a word');
            $letters = str_split($guess);

            try {
                $result = $game->guess($guess);

                foreach($result as $index => $letterResult) {
                    if ($letterResult === Result::Correct) {
                        $this->output->write('<bg=green>' . $letters[$index] . '</>');
                    } elseif ($letterResult === Result::InWord) {
                        $this->output->write('<bg=yellow>' . $letters[$index] . '</>');
                    } else {
                        $this->output->write($letters[$index]);
                    }
                }
                $this->output->newLine();

                if ($game->hasWon()) {
                    $this->info('You won! Congratulations!');
                }

                if ($game->hasLost()) {
                    $this->error('You lost! The word was ' . $game->getCurrentWord());
                }
            } catch (Exception $e) {
                $this->error($e->getMessage());
            }
         }

        return parent::SUCCESS;
    }
}
