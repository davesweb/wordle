@extends('blog.layout')

@section('content')
    <h1>Wordle</h1>
    <p>
        If you have been anywhere on social media the last couple of months, you’ve probably heard of the game Wordle. The game has become very popular in a very short
        amount of time.
    </p>
    <p>
        The rules of the game are very simple. You have 6 tries to guess a 5-letter word. After every guess the game tells you which letters are in the correct position by
        coloring them green, which letters are in the word but on a different position by coloring them yellow, and which letters are not in the word. Guess the word within
        6 tries, and you win, if you don’t you loose.
    </p>
    <p style="text-align: center">
        <img src="https://miro.medium.com/max/1400/1*LtgJ2YNxVVylG-SaE2fF9w.png" />
    </p>
    <p>
        That got me thinking. There are 26 letters in the English alphabet, and you have 6 guesses with a total number of 30 letters. So in theory you could use your first
        5 guesses to try out words that all contain different letters and thus have used 25 out of the 26 letters in the alphabet. This would give you most of the letters
        of the word to guess, and probably some in the correct position.
    </p>
    <p>
        And do you really need 25 unique letters to find a strategy that lets you win most of the time? What word should be your first word, and what should be your
        second word, and so on, to win as many games as possible?
    </p>
    <p>
        To answer these questions we're going to build a Wordle game in PHP and Laravel. We'll start small by creating the game logic and continue on after that by
        adding a nice user interface. Then we'll create a few different algorithms that can play our Wordle game and see if we can figure out what the best strategy is.
        And finally we'll make the game a little more interesting by adding a score, a game-over scenario, some mini-games and a way to share you highs-core and challenge
        your friends.
    </p>
    <ul>
        <li><a href="{{ route('blog.chapter-1') }}">Chapter 1 - A simple Wordle game in PHP and Laravel</a></li>
        <li><a href="{{ route('blog.chapter-2') }}">Chapter 2 - Using Tailwind and Livewire to add a nice user interface</a></li>
        <li><a href="{{ route('blog.chapter-3') }}">Chapter 3 - Creating algorithms to play the game</a></li>
        <li><a href="{{ route('blog.chapter-4') }}">Chapter 4 - Benchmarking: What is the best strategy</a></li>
        <li><a href="{{ route('blog.chapter-5') }}">Chapter 5 - Adding more features</a></li>
        <li><a href="{{ route('blog.chapter-6') }}">Chapter 6 - Challenge your friends</a></li>
    </ul>
    <p>
        The finished code for this game is available as open source on Github. Commits in the repository are roughly in line with the steps we take in these posts, so you
        can follow along.
    </p>
    <h2>What do you need?</h2>
    <p>
        You can checkout the repository and look at the git log to follow along with me. But if you want create your own Wordle game in PHP and Laravel, you'll need this:
    </p>
    <ul>
        <li>A fresh Laravel 9 install</li>
        <li>A little knowledge on Laravel, Tailwind and Livewire</li>
    </ul>
    <p>
        That's it! I'll explain everything else along the way.
    </p>
    <p>
        <a href="{{ route('blog.chapter-1') }}">Let's get started!</a>
    </p>
@endsection
