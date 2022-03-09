<?php

namespace App\Game;

use App\Game\Words\WordGenerator;
use Illuminate\Session\Store;

class Game
{
    public function __construct(
        private WordGenerator $dictionary,
        private Store $session,
        private int $maxTries = 6,
    ) {
    }

    public function start(): void
    {
        $this->session->put('currentWord', $this->dictionary->getRandomWord());
        $this->session->put('guesses', []);
    }

    public function guess(string $guess): array
    {
        $this->addGuess($guess);
    }

    public function hasWon(): bool
    {
        // If the last guess is equal to the current word, the game is won
        return $this->getCurrentWord() === $this->getLastGuess();
    }

    public function hasLost(): bool
    {
        // If the maximum number of tries is reached and the word is still not correct, the game is lost
        return $this->countGuesses() >= $this->maxTries && !$this->hasWon();
    }

    private function addGuess(string $guess): void
    {
        $guesses = $this->getGuesses();

        $guesses[] = $guess;

        $this->session->put('guesses', $guesses);
    }

    private function getCurrentWord(): string
    {
        return $this->session->get('currentWord');
    }

    public function getGuesses(): array
    {
        return $this->session->get('guesses', []);
    }

    public function countGuesses(): int
    {
        return count($this->getGuesses());
    }

    public function getLastGuess(): ?string
    {
        $guesses = $this->getGuesses();

        if (empty($guesses)) {
            return null;
        }

        return end($guesses);
    }
}