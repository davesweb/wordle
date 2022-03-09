<?php

namespace App\Game\Words;

class TextWordProvider implements WordProvider
{
    public function __construct(
        protected string $path
    ) {
    }

    public function getWords(): array
    {
        $contents = file_get_contents($this->path);

        return array_map(
            fn (string $word) => strtolower($word),
            explode(PHP_EOL, $contents),
        );
    }
}