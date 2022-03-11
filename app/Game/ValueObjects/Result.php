<?php

namespace App\Game\ValueObjects;

class Result
{
    public function __construct(
        public array $wordleResult,
        public int $score,
        public int $scoreMutation,
        public int $lives,
        public int $livesMutation,
        public bool $hasWon,
        public bool $hasLost,
        public ?string $currentWord = null,
    ) {
    }
}