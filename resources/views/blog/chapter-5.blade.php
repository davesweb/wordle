@extends('blog.layout')

@section('content')
    <h1>New features</h1>
    <p>
        So far, we've created a nice looking Wordle game in PHP and Laravel, and we've created and tested some
        algorithms to find the best strategy for winning as much as possible.
    </p>
    <p>
        But to be honest, the default Wordle game is pretty boring. You guess a word, you get it or not, and do it all
        again. What if we could make the game a little more interesting? We could add a points system for instance.
        Every time you guess the word correctly you earn points, the fewer tries you need, the more points you earn.
        And we could add some consequences to not guessing a word correctly, like losing a life. Once all your lives are
        gone, it's game over and your points are reset.
    </p>
    <p>
        We could also give the player some more help, like trading some of your points for an extra guess. Or trading
        some of you points for a hint, like show one of the letters in the correct spot. And finally we might want to
        switch things up by randomly changing the length of the word to guess, the longer the word the more points
        you can earn.
    </p>
    <p>
        So that's what we're going to do today. Let's get started!
    </p>
@endsection