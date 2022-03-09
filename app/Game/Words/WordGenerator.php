<?php

namespace App\Game\Words;

class WordGenerator
{
    public function __construct(
        private WordProvider $provider
    ) {
    }

    public function getRandomWord(): string
    {
        $words = $this->provider->getWords();

        return $words[array_rand($words)];
    }

    public function existsInList(string $word): bool
    {
        return in_array(strtolower($word), $this->provider->getWords(), true);
    }
}