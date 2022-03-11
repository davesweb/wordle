<?php

namespace App\Http\Livewire;

use App\Game\Game as MainGame;
use Exception;
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

            if ($this->game->isPlaying()) {
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

            $this->guesses[$guess] = $result->wordleResult;

            $this->letters = [];

            if ($result->hasLost) {
                $this->error = 'You lost! The word was ' . $result->currentWord . '. Press enter to try again.';

                if ($result->lives < 1) {
                    $this->error = 'Game over! The word was ' . $result->currentWord . '. Your score is ' . $result->score . '. Press enter to start again.';
                }
            }

            if ($result->hasWon) {
                $this->success = 'You won! Congratulations! Press enter for the next word.';
            }

            $this->score = $result->score;
            $this->scoreMutation = $result->scoreMutation;
            $this->lives = $result->lives;
            $this->livesMutation = $result->livesMutation;
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
}
