<?php

namespace App\Http\Livewire;

use App\Game\Game as MainGame;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Livewire\Component;

/**
 * @property-read MainGame $game
 */
class Game extends Component
{
    protected $listeners = [
        'addLetter' => 'addLetter',
    ];

    public int $maxTries = 6;

    public int $defaultMaxTries = 6;

    public array $guesses = [];

    public array $keyboard = [
        ['Q', 'W', 'E', 'R', 'T', 'Y', 'U', 'I', 'O', 'P'],
        ['A', 'S', 'D', 'F', 'G', 'H', 'J', 'K', 'L'],
        ['⌫', 'Z', 'X', 'C', 'V', 'B', 'N', 'M', 'Enter'],
    ];

    public ?string $error = null;

    public ?string $success = null;

    public array $letters = [];

    public int $score = 0;

    public int $scoreMutation = 0;

    public int $lives = 0;

    public int $livesMutation = 0;

    public bool $canBuyLetter = false;

    public bool $turnBought = false;

    public function mount(): void
    {
        $this->game->start();
        $this->lives = $this->game->getCurrentLives();

        $this->newWord();
    }

    public function newWord(): void
    {
        if ($this->game->getCurrentLives() > 0) {
            $this->game->startWordleGame();
        } else {
            $this->game->start();

            $this->lives = $this->game->getCurrentLives();
            $this->score = $this->game->getScore();
            $this->livesMutation = 0;
            $this->scoreMutation = 0;
        }

        $this->guesses = [];
        $this->letters = [];
        $this->success = null;
        $this->error = null;
        $this->canBuyLetter = $this->game->canBuyLetter();
        $this->turnBought = false;
        $this->maxTries = $this->defaultMaxTries;
    }

    public function render(): View
    {
        return \view('livewire.game');
    }

    public function addLetter(string $letter): void
    {
        if (in_array(strtoupper($letter), range('A', 'Z'), true) && count($this->letters) < 5 && $this->game->isPlaying()) {
            $this->error = null;
            $this->success = null;

            $this->letters[] = strtoupper($letter);
        }

        if ($letter === '⌫' || $letter === 'Backspace' && $this->game->isPlaying()) {
            $this->error = null;
            $this->success = null;

            array_pop($this->letters);
        }

        if ($letter === 'Enter') {
            $this->error = null;
            $this->success = null;

            if ($this->game->isPlaying() && !$this->turnBought) {
                $this->guess();
            } else {
                $this->newWord();
            }
        }
    }

    public function letterUsed(string $letter): bool
    {
        foreach($this->guesses as $guess => $result) {
            if (in_array($letter, str_split($guess))) {
                return true;
            }
        }

        return false;
    }

    public function guess()
    {
        $guess = implode('', $this->letters);

        try {
            $result = $this->game->guess($guess);

            Log::critical('result', (array)$result);

            $this->guesses[$guess] = $result->wordleResult;

            $this->letters = [];

            if ($result->hasLost && !$result->canBuyTurn) {
                $this->game->markLost();

                $this->error = 'You lost! The word was ' . $result->currentWord . '. Press enter to try again.';

                if ($result->lives < 1) {
                    $this->error = 'Game over! The word was ' . $result->currentWord . '. Your score is ' . $result->score . '. Press enter to start again.';
                }
            }

            if ($result->hasLost && $result->canBuyTurn) {
                $this->error = 'You lost! You can buy an extra turn for '.$this->game->getBuyTurnPrice().' points if you would like to keep going.';
            }

            if ($result->hasWon) {
                $this->success = 'You won! Congratulations! Press enter for the next word.';
            }

            $this->score = $result->score;
            $this->scoreMutation = $result->scoreMutation;
            $this->lives = $result->lives;
            $this->livesMutation = $result->livesMutation;
            $this->canBuyLetter = $this->game->canBuyLetter();
        } catch (Exception $e) {
            $this->error = $e->getMessage();
        }
    }

    public function getGameProperty(): MainGame
    {
        return new MainGame(resolve('session.store'), 3);
    }

    public function getWordProperty(): string
    {
        return $this->game->currentWord();
    }

    public function getBoughtLetter(int $index): ?string
    {
        return $this->game->getBoughtLetter()[$index] ?? null;
    }

    public function buyLetter(): array
    {
        // Reset the letters to make sure the player sees the bought letter.
        $this->letters = [];

        $result = $this->game->buyLetter();

        $this->scoreMutation = $this->game->getBuyLetterPrice() * -1;
        $this->score = $this->game->getScore();
        $this->canBuyLetter = $this->game->canBuyLetter();

        return $result;
    }

    public function buyTurn(): void
    {
        ++$this->maxTries;
        $this->letters = [];
        $this->scoreMutation = $this->game->getBuyTurnPrice();
        $this->game->buyTurn();
        $this->turnBought = true;
        $this->error = null;
    }

    public function canBuyTurn(): bool
    {
        return $this->game->canBuyTurn();
    }
}
