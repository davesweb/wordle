<?php

namespace App\Game\Players;

use App\Game\Game;
use App\Game\Words\WordProvider;

class RandomWordPlayer extends WordPlayer
{
    public function solve(Game $game, WordProvider $dictionary, int &$tries = 0): string|array
    {
        $results = [];
        $options = $this->getRemainingPossibilities($dictionary, $results);

        for ($i = 0; $i < $game->getMaxTries(); $i++) {
            $options = $this->getRemainingPossibilities($dictionary, $results);

            $guess = $options[array_rand($options)];

            $results[$guess] = $game->guess($guess);
            $tries++;

            if ($game->hasWon()) {
                return $guess;
            }
        }

        return $options;
    }

    public function getName(): string
    {
        return 'Random word solver';
    }

    public function getDescription(): string
    {
        return 'Tries a random word from the list of remaining words until it wins or runs out of turns.';
    }
}