<?php

namespace App\Game;

use App\Game\Words\WordProvider;

interface Player
{
    public function solve(Wordle $game, WordProvider $dictionary, int &$tries = 0): string|array;

    public function getName(): string;

    public function getDescription(): string;
}