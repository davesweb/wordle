<?php

namespace App\Http\Livewire;

use App\Game\Enums\Result;
use App\Game\Game as WordleGame;
use App\Game\Words\WordGenerator;
use Exception;
use Illuminate\View\View;
use Livewire\Component;

/**
 * @property-read WordleGame $wordleGame
 */
class Game extends Component
{
    protected $listeners = [
        'addLetter' => 'addLetter',
    ];

    public int $maxTries = 6;

    public array $guesses = [];

    public array $keyboard = [
        ['Q', 'W', 'E', 'R', 'T', 'Y', 'U', 'I', 'O', 'P'],
        ['A', 'S', 'D', 'F', 'G', 'H', 'J', 'K', 'L'],
        ['⌫', 'Z', 'X', 'C', 'V', 'B', 'N', 'M', 'Enter'],
    ];

    public ?string $error = null;

    public ?string $success = null;

    public array $letters = [];

    public function mount(): void
    {
        $this->newGame();
    }

    public function newGame(): void
    {
        $this->wordleGame->start();

        $this->guesses = [];
        $this->letters = [];
        $this->success = null;
        $this->error = null;
    }

    public function render(): View
    {
        return \view('livewire.game');
    }

    public function addLetter(string $letter): void
    {
        if (in_array(strtoupper($letter), range('A', 'Z'), true) && count($this->letters) < 5 && $this->wordleGame->isPlaying()) {
            $this->error = null;
            $this->success = null;

            $this->letters[] = strtoupper($letter);
        }

        if ($letter === '⌫' || $letter === 'Backspace' && $this->wordleGame->isPlaying()) {
            $this->error = null;
            $this->success = null;

            array_pop($this->letters);
        }

        if ($letter === 'Enter') {
            $this->error = null;
            $this->success = null;

            if ($this->wordleGame->isPlaying()) {
                $this->guess();
            } else {
                $this->newGame();
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
            $result = $this->wordleGame->guess($guess);

            $this->guesses[$guess] = $result;

            $this->letters = [];

            if ($this->wordleGame->hasLost()) {
                $this->error = 'You lost! The word was ' . $this->wordleGame->getCurrentWord() . '. Press enter to try again.';
            }

            if ($this->wordleGame->hasWon()) {
                $this->success = 'You won! Congratulations! Press enter for the next word.';
            }
        } catch (Exception $e) {
            $this->error = $e->getMessage();
        }
    }

    public function getWordleGameProperty(): WordleGame
    {
        return new WordleGame(
            resolve(WordGenerator::class),
            resolve('session.store'),
            $this->maxTries,
        );
    }
}
