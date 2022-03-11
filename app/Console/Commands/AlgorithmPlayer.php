<?php

namespace App\Console\Commands;

use App\Game\Wordle;
use App\Game\Player;
use App\Game\Players\CraneNymphFjord;
use App\Game\Players\QuickNymphBoard;
use App\Game\Players\QuickWaltzFjord;
use App\Game\Players\RandomWordPlayer;
use App\Game\Words\TextWordProvider;
use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\ProgressBar;

class AlgorithmPlayer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'play:algo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Choose an algorithm and let it play the game multiple times and see the results.';

    public function handle(Wordle $game): int
    {
        $algorithm = $this->choice('Please choose an algorithm', $this->getAlgorithms());
        $rounds = $this->ask('How many times should ' . ($algorithm === 'all' ? 'each' : 'this') . ' algorithm play the game', 500);

        if ($algorithm === 'all') {
            return $this->playAllAlgorithms($rounds, $game);
        }

        $wins = 0;
        $triesResult = [];

        $this->withProgressBar($rounds, function(ProgressBar $bar) use ($rounds, $algorithm, $game, &$wins, &$triesResult) {
            for ($i = 0; $i < $rounds; $i++) {
                $tries = 0;
                /** @var Player $player */
                $player = resolve($algorithm);

                $game->start();

                $result = $player->solve($game, new TextWordProvider(resource_path('game/words.txt')), $tries);

                $bar->advance();

                if (is_string($result)) {
                    ++$wins;
                    $triesResult[] = $tries;
                }
            }
        });

        $this->newLine(2);

        $avgTries = array_sum($triesResult) / count($triesResult);

        $this->table($this->tableHeadings(), [[
            $algorithm,
            $wins,
            round(($wins / $rounds) * 100, 2) . '%',
            round($avgTries, 4) . ' (' . ceil($avgTries) . ')',
            $rounds - $wins,
            round((($rounds - $wins) / $rounds) * 100, 2) . '%',
        ]]);

        return parent::SUCCESS;
    }

    public function playAllAlgorithms(int $rounds, Wordle $game): int
    {
        $rows = [];

        $this->withProgressBar((count($this->getAlgorithms()) - 1) * $rounds, function(ProgressBar $bar) use ($rounds, $game, &$rows) {
            foreach ($this->getAlgorithms() as $algorithm) {
                if ($algorithm === 'all') {
                    continue;
                }

                $wins = 0;
                $triesResult = [];

                for ($i = 0; $i < $rounds; $i++) {
                    $tries = 0;
                    /** @var Player $player */
                    $player = resolve($algorithm);

                    $game->start();

                    $result = $player->solve($game, new TextWordProvider(resource_path('game/words.txt')), $tries);

                    $bar->advance();

                    if (is_string($result)) {
                        ++$wins;
                        $triesResult[] = $tries;
                    }
                }

                $avgTries = array_sum($triesResult) / count($triesResult);

                $rows[] = [
                    $algorithm,
                    $wins,
                    round(($wins / $rounds) * 100, 2) . '%',
                    round($avgTries, 4) . ' (' . ceil($avgTries) . ')',
                    $rounds - $wins,
                    round((($rounds - $wins) / $rounds) * 100, 2) . '%',
                ];
            }
        });

        $this->newLine(2);
        $this->table($this->tableHeadings(), $rows);

        return parent::SUCCESS;
    }

    protected function getAlgorithms(): array
    {
        return [
            'all',
            RandomWordPlayer::class,
            QuickWaltzFjord::class,
            QuickNymphBoard::class,
            CraneNymphFjord::class,
        ];
    }

    private function tableHeadings(): array
    {
        return [
            'Algorithm used',
            'Wins',
            'Wins (%)',
            'Average tries to win',
            'Lost',
            'Lost (%)',
        ];
    }
}
