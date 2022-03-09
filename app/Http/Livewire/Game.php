<?php

namespace App\Http\Livewire;

use App\Game\Enums\Result;
use App\Game\Game as WordleGame;
use App\Game\Words\WordGenerator;
use Exception;
use Illuminate\View\View;
use Livewire\Component;

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
        $this->wordleGame()->start();

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
        if (in_array(strtoupper($letter), range('A', 'Z'), true) && count($this->letters) < 5 && $this->wordleGame()->isPlaying()) {
            $this->letters[] = strtoupper($letter);
        }

        if ($letter === '⌫' || $letter === 'Backspace' && $this->wordleGame()->isPlaying()) {
            array_pop($this->letters);
        }

        if ($letter === 'Enter') {
            if ($this->wordleGame()->isPlaying()) {
                $this->guess();
            } else {
                $this->newGame();
            }
        }

        $this->error = null;
        $this->success = null;
    }

    public function guess()
    {
        $guess = implode('', $this->letters);

        try {
            $result = $this->wordleGame()->guess($guess);

            $this->guesses[$guess] = $result;

            $this->letters = [];
        } catch (Exception $e) {
            $this->error = $e->getMessage();

            $this->emit('$refresh');
        }
    }

    protected function wordleGame(): WordleGame
    {
        return new WordleGame(
            resolve(WordGenerator::class),
            resolve('session.store'),
            6
        );
    }
}
