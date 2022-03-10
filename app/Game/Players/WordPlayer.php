<?php

namespace App\Game\Players;

use App\Game\Enums\Result;
use App\Game\Player;
use App\Game\Words\WordProvider;

abstract class WordPlayer implements Player
{
    protected function getRemainingPossibilities(WordProvider $dictionary, array $resultsSoFar): array
    {
        $allWords = $dictionary->getWords();

        $knownPositions = [];

        foreach($resultsSoFar as $guess => $result) {
            foreach($result as $index => $letter) {
                if ($letter === Result::Correct) {
                    $knownPositions[$index] = $guess[$index];
                }
            }
        }

        $lettersInWord = [];

        foreach($resultsSoFar as $guess => $result) {
            foreach($result as $index => $letter) {
                if ($letter === Result::InWord) {
                    $lettersInWord[] = $guess[$index];
                }
            }
        }

        $possibilities = [];

        foreach($allWords as $word) {
            if (in_array($word, array_keys($resultsSoFar), true)) {
                continue;
            }

            $letters = str_split($word);

            foreach($knownPositions as $index => $knownLetter) {
                if ($letters[$index] !== $knownLetter) {
                    continue 2;
                }
            }

            foreach ($lettersInWord as $letterInWord) {
                if (str_contains($word, $letterInWord) === false) {
                    continue 2;
                }
            }

            $possibilities[] = $word;
        }

        return array_values(array_unique($possibilities));
    }
}