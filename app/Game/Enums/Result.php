<?php

namespace App\Game\Enums;

enum Result: int
{
    case Incorrect = 0;

    case Correct = 1;

    case InWord = 2;
}