<?php

namespace App\Game;

use App\Game\Enums\Result as ResultEnum;
use App\Game\ValueObjects\Result;
use App\Game\Words\TextWordProvider;
use App\Game\Words\WordGenerator;
use App\Game\Words\WordProvider;
use Illuminate\Session\Store;

final class Game
{
    private Wordle $wordle;

    public function __construct(
        private Store $session,
        int $startingLives = 3,
    ) {
        $this->session->put('startingLives', $startingLives);

        $this->wordle = $this->createWordleGame(new TextWordProvider(resource_path('game/words.txt')));
    }

    public function getScore(): int
    {
        return $this->session->get('score');
    }

    public function getCurrentLives(): int
    {
        return $this->session->get('currentLives');
    }

    public function start(int $score = 0, ?int $lives = null): void
    {
        $this->session->put('score', $score);
        $this->session->put('currentLives', $lives ?? $this->session->get('startingLives'));
        $this->session->put('gamesPlayed', 0);
        $this->session->put('boughtLetter');
        $this->session->put('buyLetterPrice', $this->wordle->getMaxTries() * 25);
    }

    public function createWordleGame(WordProvider $dictionary, int $maxTries = 6): Wordle
    {
        return new Wordle(new WordGenerator($dictionary), $this->session, $maxTries);
    }

    public function startWordleGame(): void
    {
        $this->session->put('boughtLetter');

        $this->wordle->start();
    }

    public function guess(string $guess): Result
    {
        // todo: keep track of games played
        $result = $this->wordle->guess($guess);

        if ($this->wordle->hasWon()) {
            $tries = $this->wordle->countGuesses();

            $score = $this->calculateScore(strlen($guess), $this->wordle->getMaxTries(), $tries);
            $newScore = $this->addScore($score);

            $lives = $this->calculateLives($this->wordle->getMaxTries(), $tries);
            $newLives = $this->addLives($lives);

            $this->session->put('boughtLetter');

            return new Result($result, $newScore, $score, $newLives, $lives, true, false);
        }

        if ($this->wordle->hasLost()) {
            $newLives = $this->addLives(-1);

            $this->session->put('boughtLetter');

            return new Result($result, $this->getScore(), 0, $newLives, -1, false, true, $this->wordle->getCurrentWord());
        }

        // Didn't win, didn't lose, still playing
        return new Result($result, $this->getScore(), 0, $this->getCurrentLives(), 0, false, false);
    }

    public function isPlaying(): bool
    {
        return $this->wordle->isPlaying();
    }

    private function addScore(int $points): int
    {
        $score = $this->session->get('score');

        $newScore = $score + $points;

        $this->session->put('score', $newScore);

        return $newScore;
    }

    private function calculateScore(int $wordLength, int $maxTries, int $tries): int
    {
        // Each word is worth 10 times the amount of letters in contains, so for 5-letter words the word score is 50
        $wordScore = 10 * $wordLength;

        // The base score you start with is the maximum number of tries times the word score, so for 6 tries of
        // 5-letter words, the base score is 300
        $baseScore = $wordScore * $maxTries;

        // For every time you try minus one (the minus one is because always need at least 1 try) you loose the
        // word score. So with a base score of 300, you get:
        // 1 guess: 300
        // 2 guesses: 250
        // 3 guesses: 200
        // 4 guesses: 150
        // 5 guesses: 100
        // 6 guesses: 50
        $score = $baseScore - (($tries - 1) * $wordScore);

        // If you guess the correct word within the first 1/3 of the available guesses you get bonus points
        // The bonus is the base score divided by the number of tries times the maximum number of tries.
        // So with a base score of 300 and 6 maximum guesses, you get:
        // 1 guess: 300 / 1 * 6 = 50
        // 2 guesses: 300 / 2 * 6 = 25
        // 3 guesses or more: 0 because that's more than 1/3 of the available guesses
        $bonus = $tries < $maxTries / 3 ? floor($baseScore / ($tries * $maxTries)) : 0;

        return $score + $bonus;
    }

    private function addLives(int $lives): int
    {
        $currentLives = $this->session->get('currentLives');

        $newLives = $currentLives + $lives;

        $this->session->put('currentLives', $newLives);

        return $newLives;
    }

    private function calculateLives(int $maxTries, int $tries): int
    {
        return floor($maxTries / ($tries * 3));
    }

    // temp function, remove before deploy
    public function currentWord(): string
    {
        return $this->wordle->getCurrentWord();
    }

    public function canBuyLetter(): bool
    {
        // A player can buy a letter when he is at a try that doesn't reward extra lives if he guesses correctly, if he hasn't bought a letter before and if he
        // has enough points to spend. He can also only buy a letter while paying.
        if (!$this->wordle->isPlaying()) {
            return false;
        }

        if ($this->wordle->countGuesses() === 0 || $this->calculateLives($this->wordle->getMaxTries(), $this->wordle->countGuesses()) > 0) {
            return false;
        }

        if ($this->getBoughtLetter() !== null) {
            return false;
        }

        if ($this->getScore() < $this->getBuyLetterPrice()) {
            return false;
        }

        return true;
    }

    public function getBoughtLetter(): ?array
    {
        return $this->session->get('boughtLetter');
    }

    public function getBuyLetterPrice(): int
    {
        return $this->session->get('buyLetterPrice');
    }

    public function buyLetter(): array
    {
        // We don't want to give the player a letter he already knows, that would defeat the purpose of buying a letter in the first place. So check all
        // previous guesses and return a letter the player doesn't know yet.
        $maxIndex = strlen($this->wordle->getCurrentWord()) - 1;
        $indexesAvailable = $this->wordle->getNonGuessedIndexes();

        $this->addScore($this->getBuyLetterPrice() * -1);

        if (count($indexesAvailable) === 0) {
            // Every position is already known, return a random letter
            $randomIndex = random_int(0, $maxIndex);

            $this->session->put('boughtLetter', [$randomIndex => str_split($this->currentWord()[$randomIndex])]);

            return $this->getBoughtLetter();
        }

        // Select a random index from the list
        $randomKey = array_rand($indexesAvailable);
        $randomIndex = $indexesAvailable[$randomKey];

        $this->session->put('boughtLetter', [$randomIndex => strtoupper(str_split($this->currentWord())[$randomIndex])]);

        return $this->getBoughtLetter();
    }
}
