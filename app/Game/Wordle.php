<?php

namespace App\Game;

use App\Game\Enums\Result;
use App\Game\Words\WordGenerator;
use Exception;
use Illuminate\Session\Store;

class Wordle
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
        $guess = strtolower($guess);

        $this->assertValidGuess($guess);

        $this->addGuess($guess);

        $result = [];
        $currentWord = str_split($this->getCurrentWord());
        // Keep track of the letters left in the word which haven't been marked as green or yellow.
        // This will make sure that if the player guesses a word with repeat letters ('error' for instance), and the current
        // word contains that letter, but fewer times than the guess ('roads' for instance) only one of the repeated
        // letters in the guess is marked yellow
        $lettersLeft = str_split($this->getCurrentWord());

        foreach(str_split($guess) as $index => $letter) {
            $result[$index] = Result::Incorrect;

            if ($currentWord[$index] === $letter) {
                $result[$index] = Result::Correct;
                unset($lettersLeft[$index]);
            } elseif (in_array($letter, $lettersLeft, true)) {
                unset($lettersLeft[array_search($letter, $lettersLeft)]);
                $result[$index] = Result::InWord;
            }
        }

        return $result;
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

    public function isPlaying(): bool
    {
        return $this->getCurrentWord() !== null && !$this->hasWon() && !$this->hasLost();
    }

    private function assertValidGuess(string $guess): void
    {
        if (strlen($guess) !== strlen($this->getCurrentWord())) {
            throw new Exception('Incorrect length. The length should be ' . strlen($this->getCurrentWord()) . ' letters long.');
        }

        if (!$this->dictionary->existsInList($guess)) {
            throw new Exception('Invalid word.');
        }

        if (in_array($guess, $this->getGuesses(), true)) {
            throw new Exception('Already tried that word. Try another word.');
        }
    }

    private function addGuess(string $guess): void
    {
        $guesses = $this->getGuesses();

        $guesses[] = $guess;

        $this->session->put('guesses', $guesses);
    }

    public function getCurrentWord(): ?string
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

    public function getMaxTries(): int
    {
        return $this->maxTries;
    }
}