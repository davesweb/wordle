<?php

namespace App\Game\Players;

use App\Game\Game;
use App\Game\Words\WordProvider;

abstract class FixedWordSolver extends WordPlayer
{
    protected array $words = [];

    public function solve(Game $game, WordProvider $dictionary, int &$tries = 0): string|array
    {
        $resultsSoFar = [];
        $remainingPossibilities = $this->getRemainingPossibilities($dictionary, $resultsSoFar);

        foreach($this->words as $guess) {
            // If we've run out of tries, return the possibilities that are left, so we can use them in our statistics.
            if ($tries >= $game->getMaxTries()) {
                return $remainingPossibilities;
            }

            $resultsSoFar[$guess] = $game->guess($guess);
            $tries++;

            if ($game->hasWon()) {
                // One of the words in our list has won! Lucky.
                return $guess;
            }

            $remainingPossibilities = $this->getRemainingPossibilities($dictionary, $resultsSoFar);

            if (count($remainingPossibilities) === 1) {
                $game->guess($remainingPossibilities[0]);

                // If there is only 1 option left, this should always be true because the game and the player use the same dictionary, but we'll add the check just in case
                if ($game->hasWon()) {
                    return $remainingPossibilities[0];
                } else {
                    throw new \Exception('No more possibilities left!');
                }
            }
        }

        // We've run out of words to try, and we haven't won, return what is left
        return $remainingPossibilities;
    }

    public function getName(): string
    {
        return 'Fixed word solver';
    }

    public function getDescription(): string
    {
        return 'Always tries the same words  ('.implode(', ', $this->words).') until there is only 1 possibility left, until the game is won or until it ran out of tries.';
    }
}