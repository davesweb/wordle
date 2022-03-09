<?php

namespace App\Game;

use App\Game\Words\WordGenerator;

class Game
{
    public function __construct(
        private WordGenerator $dictionary,
    ) {
    }

    public function start(): void
    {
        // todo
    }

    public function guess(string $guess): array
    {
        // todo
    }

    public function hasWon(): bool
    {
        // todo
    }

    public function hasLost(): bool
    {
        // todo
    }
}