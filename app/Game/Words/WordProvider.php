<?php

namespace App\Game\Words;

interface WordProvider
{
    public function getWords(): array;
}